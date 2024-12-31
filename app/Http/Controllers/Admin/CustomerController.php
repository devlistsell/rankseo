<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\PlanGeneral;
use Acelle\Library\Facades\Hook;
use Acelle\Model\Keyword;
use Google\Client;
use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use Google\Service\SearchConsole\ApiDimensionFilter;
use Google\Service\SearchConsole\ApiDimensionFilterGroup;
// use Acelle\Services\GoogleSearchConsoleService;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    // protected $googleSearchConsole;

    // public function __construct(GoogleSearchConsoleService $googleSearchConsole)
    // {
    //     $this->googleSearchConsole = $googleSearchConsole;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        // If admin can view all customer
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Customer())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $customers = \Acelle\Model\Customer::search($request)
            ->filter($request);

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        // If admin can view all customer
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Customer())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $customers = \Acelle\Model\Customer::search($request->keyword)
            ->filter($request)
            ->orderBy($request->sort_order, $request->sort_direction ? $request->sort_direction : 'asc')
            ->paginate($request->per_page);

        return view('admin.customers._list', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = \Acelle\Model\Customer::newCustomer();
        $customer->status = 'active';
        $customer->uid = '0';

        if (!empty($request->old())) {
            $customer->fill($request->old());
        }

        // User info
        $customer->user = new \Acelle\Model\User();
        $customer->user->fill($request->old());

        // authorize
        if (\Gate::denies('create', $customer)) {
            return $this->notAuthorized();
        }

        return view('admin.customers.create', [
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $current_user = $request->user();
        $customer = \Acelle\Model\Customer::newCustomer();
        $contact = new \Acelle\Model\Contact();

        if (\Gate::denies('create', $customer)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $user = new \Acelle\Model\User();
            $user->fill($request->all());
            $user->activated = true;

            $this->validate($request, $user->rules());

            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            $customer->admin_id = $request->user()->admin->id;
            $customer->fill($request->all());
            $customer->status = 'active';

            if ($customer->save()) {
                $user->customer_id = $customer->id;
                $user->save();

                if ($request->hasFile('image')) {
                    if ($request->file('image')->isValid()) {
                        $user->uploadProfileImage($request->file('image'));
                    }
                }

                if ($request->_remove_image == 'true') {
                    $user->removeProfileImage();
                }

                Hook::execute('customer_added', [$customer]);

                $password = $request->password;
                $loginUrl = route('login');
                $emailData = [
                    'customer' => $customer,
                    'email' => $user->email,
                    'password' => $password,
                    'loginUrl' => $loginUrl,
                ];

                try {
                    Mail::send('emails.customer_thank_you', $emailData, function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Thank You for Registering');
                    });
                } catch (\Exception $e) {
                    \Log::error('Failed to send email to customer: ' . $e->getMessage());
                }

                $request->session()->flash('alert-success', trans('messages.customer.created'));

                return redirect()->action('Admin\CustomerController@index');
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $customer = \Acelle\Model\Customer::findByUid($id);
        event(new \Acelle\Events\UserUpdated($customer));

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $customer->fill($request->old());
            // User info
            $customer->user->fill($request->old());
        }

        return view('admin.customers.edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get current user
        $current_user = $request->user();
        $customer = \Acelle\Model\Customer::findByUid($id);

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        // Prenvent save from demo mod
        if (config('app.demo')) {
            return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
        }

        // save posted data
        if ($request->isMethod('patch')) {
            // Prenvent save from demo mod
            if (config('app.demo')) {
                return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
            }

            $user = $customer->user;
            $user->fill($request->all());

            $this->validate($request, $user->rules());

            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            // Save current user info
            $customer->fill($request->all());
            $customer->save();

            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $user->uploadProfileImage($request->file('image'));
                }
            }

            // Remove image
            if ($request->_remove_image == 'true') {
                $user->removeProfileImage();
            }

            if ($customer->save()) {
                $request->session()->flash('alert-success', trans('messages.customer.updated'));
                return redirect()->action('Admin\CustomerController@index');
            }
        }
    }

    /**
     * Enable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\Customer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.customers.disabled');
    }

    /**
     * Disable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\Customer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.customers.disabled');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            return response()->json(["message" => trans('messages.operation_not_allowed_in_demo')], 404);
        }

        $customers = \Acelle\Model\Customer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($customers->get() as $customer) {
            // authorize
            if (\Gate::denies('delete', $customer)) {
                return;
            }
        }

        foreach ($customers->get() as $customer) {
            // Delete Customer account but KEEP user account if it is associated with an Admin
            $customer->deleteAccount();
        }

        // Redirect to my lists page
        echo trans('messages.customers.deleted');
    }

    /**
     * Switch user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function loginAs(Request $request)
    {
        $customer = \Acelle\Model\Customer::findByUid($request->uid);

        // authorize
        if (\Gate::denies('loginAs', $customer)) {
            return $this->notAuthorized();
        }

        $orig_id = $request->user()->uid;
        \Auth::login($customer->user);
        \Session::put('orig_customer_id', $orig_id);
        return redirect()->action('HomeController@index');
    }

    /**
     * Log in back user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function loginBack(Request $request)
    {
        $id = \Session::pull('orig_customer_id');
        $orig_user = \Acelle\Model\Customer::findByUid($id);

        \Auth::login($orig_user);

        return redirect()->action('Admin\UserController@index');
    }

    /**
     * Select2 customer.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        echo \Acelle\Model\Customer::select2($request);
    }

    /**
     * User's subscriptions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscriptions(Request $request, $uid)
    {
        $customer = \Acelle\Model\Customer::findByUid($uid);

        // authorize
        if (\Gate::denies('read', $customer)) {
            return $this->notAuthorized();
        }

        return view('admin.customers.subscriptions', [
            'customer' => $customer
        ]);
    }

    /**
     * Customers growth chart content.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function growthChart(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'data' => [],
        ];

        // columns
        for ($i = 4; $i >= 0; --$i) {
            $result['columns'][] = \Carbon\Carbon::now()->subMonthsNoOverflow($i)->format('m/Y');
            $result['data'][] = \Acelle\Model\Customer::customersCountByTime(
                \Carbon\Carbon::now()->subMonthsNoOverflow($i)->startOfMonth(),
                \Carbon\Carbon::now()->subMonthsNoOverflow($i)->endOfMonth(),
                $request->user()->admin
            );
        }

        return response()->json($result);
    }

    /**
     * Update customer contact information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function contact(Request $request, $uid)
    {
        // Get current user
        $customer = \Acelle\Model\Customer::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        if ($customer->contact) {
            $contact = $customer->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                'first_name' => $customer->user->first_name,
                'last_name' => $customer->user->last_name,
                'email' => $customer->user->email,
            ]);
        }

        // Create new company if null
        if (!$contact) {
            $contact = new \Acelle\Model\Contact();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                $customer->contact_id = $contact->id;
                $customer->save();
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        return view('admin.customers.contact', [
            'customer' => $customer,
            'contact' => $contact->fill($request->old()),
        ]);
    }

    /**
     * Customer's sub-account list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function subAccount(Request $request, $uid)
    {
        // Get current user
        $customer = \Acelle\Model\Customer::findByUid($uid);

        // authorize
        if (\Gate::denies('viewSubAccount', $customer)) {
            return redirect()->action('Admin\CustomerController@edit', $customer->uid);
        }

        return view('admin.customers.sub_account', [
            'customer' => $customer
        ]);
    }

    /**
     * Assign plan to customer.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function assignPlan(Request $request, $uid)
    {
        $customer = \Acelle\Model\Customer::findByUid($uid);
        $plans = PlanGeneral::active()->get();

        // authorize
        if (\Gate::denies('assignPlan', $customer)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan = PlanGeneral::findByUid($request->plan_uid);

            try {
                $customer->assignGeneralPlan($plan);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.customer.plan.assigned', [
                    'plan' => $plan->name,
                    'customer' => $customer->displayName(),
                ]),
            ], 201);
        }

        return view('admin.customers.assign_plan', [
            'customer' => $customer,
            'plans' => $plans,
        ]);
    }

    public function oneClickLogin(Request $request)
    {
        $customer = \Acelle\Model\Customer::findByUid($request->uid);

        return view('admin.customers.oneClickLogin', [
            'url' => $customer->user->generateOneClickLoginUrl(),
        ]);
    }

    public function keywords(Request $request, $uid)
    {
        // Get current user
        $customer = \Acelle\Model\Customer::findByUid($uid);
        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        if ($customer->contact) {
            $contact = $customer->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                'first_name' => $customer->user->first_name,
                'last_name' => $customer->user->last_name,
                'email' => $customer->user->email,
            ]);
        }

        // Create new company if null
        if (!$contact) {
            $contact = new \Acelle\Model\Contact();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                $customer->contact_id = $contact->id;
                $customer->save();
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        $keywords = Keyword::where('uid', $customer->id)->latest()->paginate(10);
        return view('admin.customers.keywords', [
            'customer' => $customer,
            'contact' => $contact->fill($request->old()),
            'keywords' => $keywords,
        ]);
    }

    public function search_keywords(Request $request)
    {
        $clients = \Acelle\Model\User::select('id','website')->where('id','!=',1)->where('website','!=','')->get();
        foreach($clients as $key=>$client){
            $allKey = \Acelle\Model\keyword::where('uid', $client->id)->pluck('keyword')->toArray();
            if($allKey){dd($allKey);
                $rankings = \Acelle\Helpers\keywordSearch($allKey, 'https://sa-kat.de');
                $totalRanks = [];
                foreach ($rankings as $val) {
                    if ($val['found']) {
                        $keyId = \Acelle\Model\keyword::where('keyword', $val['keyword'])->first()->id;
                        if(isset($keyId)){
                            $totalRanks[] = [
                                'uid' => $client['id'],
                                'keyword_id' => $keyId,
                                'ranking' => $val['position'],
                                'date_time' => now(),
                            ];
                        }
                    }
                }
                \Acelle\Model\KeywordHistory::insert($totalRanks);
            }
        }

        dd('yesss');
        $clientId = $request->client_id;
        $keyword = $request->keyword; // Single keyword to filter
        if (empty($keyword) || empty($clientId)) {
            return response()->json(['status' => 'empty', 'message' => 'Please add required field!']);
        }
        try {
            if (Keyword::where(['uid' => $request->client_id, 'keyword' => $request->keyword])->count() > 0) {
                return response()->json(['status' => 'exist']);
            }
            $clientDetail = \Acelle\Model\User::where('id', $clientId)->first();
            if (!$clientDetail || empty($clientDetail->website)) {
                return response()->json(['status' => 'site', 'message' => 'Client website is required!']);
            }

            $client = new Client();
            $client->setApplicationName('RankSeo');
            $client->setAuthConfig(storage_path('app/google_service_account.json')); // Path to your service account credentials
            $client->setScopes([SearchConsole::WEBMASTERS_READONLY]);

            // Create a SearchConsole service instance
            $searchConsole = new SearchConsole($client);

            // Set the site URL
            $siteUrl = $clientDetail->website;

            // Set the date range for the query
            $startDate = now()->subDays(3)->toDateString(); // Last 3 days
            $endDate = now()->toDateString(); // Current date

            // Set the request body parameters
            $requestBody = new SearchAnalyticsQueryRequest();
            $requestBody->setStartDate($startDate);
            $requestBody->setEndDate($endDate);
            $requestBody->setDimensions(['query']);  // Query dimension (keywords)
            $requestBody->setRowLimit(10);  // Limit the response to the top 10 rows

            // Define the filter for a single keyword
            $filter = new ApiDimensionFilter([
                'dimension' => 'query',
                'operator' => 'equals',
                'expression' => $keyword // Replace with the single keyword
            ]);

            // Group the filter into a filter group
            $filterGroup = new ApiDimensionFilterGroup([
                'filters' => [$filter]
            ]);

            // Add the filter group to the request body
            $requestBody->setDimensionFilterGroups([$filterGroup]);

            // Make the request to the Google API
            $response = $searchConsole->searchanalytics->query($siteUrl, $requestBody);

            // Handle the response
            $rankings = $response->getRows();
            if ($rankings) {
                // Loop through the rankings and send one key and position at a time
                foreach ($rankings as $row) {
                    // Assuming the keyword is in $row['keys'][0] and the position in $row['position']
                    $key = $row['keys'][0]; // The keyword
                    $position = $row['position']; // The position
                    return response()->json(['status' => true, 'key' => $key, 'position' => $position, 'message' => 'Successfully']);
                }
            }
            throw new \Exception('No data found for the keyword.');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to retrieve keyword data!']);
        }
    }


    // public function search_keywords(Request $request)
    // {
    //     $clientId = $request->client_id;
    //     $keyword = $request->keyword; // Single keyword to filter
    //     if (empty($keyword) || empty($clientId)) {
    //         return response()->json(['status' => 'empty', 'message' => 'Please add required field!']);
    //     }
    //     try {

    //         $clientDetail = \Acelle\Model\User::where('id', $clientId)->first();
    //         if (!$clientDetail || empty($clientDetail->website)) {
    //             return response()->json(['status' => 'site', 'message' => 'Client website is required!']);
    //         }

    //         $client = new Client();
    //         $client->setApplicationName('RankSeo');
    //         $client->setAuthConfig(storage_path('app\google_service_account.json')); // Path to your service account credentials
    //         $client->setScopes([SearchConsole::WEBMASTERS_READONLY]);

    //         // Create a SearchConsole service instance
    //         $searchConsole = new SearchConsole($client);

    //         // Set the site URL
    //         $siteUrl = $clientDetail->website; //'https://sa-kat.de';

    //         // Set the date range for the query
    //         $startDate = now()->subDays(3)->toDateString(); // Last 3 days
    //         $endDate = now()->toDateString(); // Current date

    //         // Set the request body parameters
    //         $requestBody = new SearchAnalyticsQueryRequest();
    //         $requestBody->setStartDate($startDate);
    //         $requestBody->setEndDate($endDate);
    //         $requestBody->setDimensions(['query']);  // Query dimension (keywords)
    //         $requestBody->setRowLimit(10);  // Limit the response to the top 10 rows

    //         // Define the filter for a single keyword
    //         $filter = new ApiDimensionFilter([
    //             'dimension' => 'query',
    //             'operator' => 'equals',
    //             'expression' => $keyword // Replace with the single keyword
    //         ]);

    //         // Group the filter into a filter group
    //         $filterGroup = new ApiDimensionFilterGroup([
    //             'filters' => [$filter]
    //         ]);

    //         // Add the filter group to the request body
    //         $requestBody->setDimensionFilterGroups([$filterGroup]);

    //         // Make the request to the Google API
    //         $response = $searchConsole->searchanalytics->query($siteUrl, $requestBody);

    //         // Handle the response
    //         $rankings = $response->getRows();
    //         if ($rankings) {
    //             $keys = [];
    //             $positions = [];
    //             foreach ($rankings as $row) {
    //                 $keys[] = $row['keys'][0];
    //                 $positions[] = $row['position'];
    //             }
    //             return response()->json(['status' => true, 'key' => $keys, 'ranking' => $positions]);
    //         }
    //         throw new \Exception('Failed to save the keyword.');
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false, 'message' => 'Failed to add keyword!']);
    //     }
    // }

    public function save_keywords(Request $request)
    {
        try {
            if (empty($request->client_id) || empty($request->keyword) || empty($request->ranking) || empty($request->difficulty)) {
                return response()->json(['status' => 'error', 'message' => 'Please add required field!']);
            }

            if (Keyword::where(['uid' => $request->client_id, 'keyword' => $request->keyword])->count() > 0) {
                return response()->json(['status' => '0']);
            }
            $keyword = new \Acelle\Model\Keyword();
            $keyword->uid = $request->client_id;
            $keyword->keyword = $request->keyword;
            $keyword->ranking = round($request->ranking, 2);
            $keyword->difficulty_id = $request->difficulty;

            if ($keyword->save()) {
                $request->session()->flash('alert-success', 'keyword added successfully');
                return response()->json(['status' => true]);
            }
            throw new \Exception('Failed to save the keyword.');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to add keyword!']);
        }
    }

    // public function search_keywords(Request $request)
    // {
    //     $clientId = $request->client_id;
    //     $keyword = $request->keyword;

    //     // Set up the Google Client
    //     $client = new Client();
    //     $client->setApplicationName('Your Application Name');
    //     $client->setAuthConfig(storage_path('app\google_service_account.json')); // Path to your service account credentials
    //     $client->setScopes([SearchConsole::WEBMASTERS_READONLY]);

    //     // dd($client);
    //     // Create a SearchConsole service instance
    //     $searchConsole = new SearchConsole($client);

    //     // Set the site URL (URL encoded)
    //     $siteUrl = 'https://sa-kat.de'; // Example: replace with the actual site URL
    //     $encodedSiteUrl = urlencode($siteUrl);

    //     // Set the API endpoint
    //     $apiEndpoint = "https://www.googleapis.com/webmasters/v3/sites/https%3A%2F%2Fsa-kat.de/searchAnalytics/query";

    //     // Set the date range for the query
    //     $startDate = now()->subDays(3)->toDateString(); // Last 3 days
    //     $endDate = now()->toDateString(); // Current date

    //     // Set the request body parameters
    //     $requestBody = new SearchAnalyticsQueryRequest();
    //     $requestBody->setStartDate($startDate);
    //     $requestBody->setEndDate($endDate);
    //     $requestBody->setDimensions(['query']);  // Query dimension (keywords)
    //     $requestBody->setRowLimit(10);  // Limit the response to the top 10 rows

    //     // Make the request to the Google API
    //     $response = $searchConsole->searchanalytics->query($siteUrl, $requestBody);

    //     // Handle the response
    //     $rankings = $response->getRows();

    //     // Debug and inspect the rankings data
    //     dd($rankings);

    //     // Return view with the rankings data
    //     return view('keyword-rankings', compact('rankings'));


    //     // $siteUrl = 'https://www.googleapis.com/webmasters/v3/sites/https%3A%2F%2Fsa-kat.de/searchAnalytics/query';
    //     // $startDate = now()->subDays(3)->toDateString(); // Last 30 days
    //     // $endDate = now()->toDateString();

    //     // $rankings = $this->googleSearchConsole->getKeywordRankings($siteUrl, $startDate, $endDate);
    //     // dd($rankings);
    //     // return view('keyword-rankings', compact('rankings'));
    // }

    public function invoices(Request $request, $uid)
    {
        // Get current user
        $customer = \Acelle\Model\Customer::findByUid($uid);
        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        if ($customer->contact) {
            $contact = $customer->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                'first_name' => $customer->user->first_name,
                'last_name' => $customer->user->last_name,
                'email' => $customer->user->email,
            ]);
        }

        // Create new company if null
        if (!$contact) {
            $contact = new \Acelle\Model\Contact();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                $customer->contact_id = $contact->id;
                $customer->save();
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        return view('admin.customers.invoices', [
            'customer' => $customer,
            'contact' => $contact->fill($request->old()),
        ]);
    }
}
