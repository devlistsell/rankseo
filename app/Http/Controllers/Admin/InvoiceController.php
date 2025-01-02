<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Invoice;
use Acelle\Model\Transaction;
use Acelle\Model\Customer;
use DataTables;
use Acelle\Model\NewInvoice;

class InvoiceController extends Controller
{
    public function download(Request $request)
    {
        $invoice = Invoice::findByUid($request->uid);

        return \Response::make($invoice->exportToPdf(), 200, [
            'Content-type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-'.$invoice->uid.'.pdf"',
        ]);
    }

    public function templateEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            \Acelle\Model\Setting::set('invoice.custom_template', $request->content);
        }

        return view('admin.invoices.templateEdit');
    }

    public function index(Request $request)
    {
        return view('admin.invoices.index');
    }

    public function list(Request $request)
    {
        $invoices = Invoice::select('invoices.*');

        // customer filter
        if (isset($request->customer_uid)) {
            $customer = Customer::findByUid($request->customer_uid);
            $invoices = $invoices->where('customer_id', $customer->id);
        }

        // type filter
        if (isset($request->type)) {
            $invoices = $invoices->where('type', $request->type);
        }

        // status filter
        if (isset($request->status)) {
            if ($request->status == 'pending') {
                $invoices = $invoices->pending();
            } else {
                $invoices = $invoices->notPending()->where('status', $request->status);
            }
        }

        // sort
        if (!empty($request->sort_order)) {
            $invoices = $invoices->orderBy($request->sort_order, $request->sort_direction);
        }

        // pagination
        $invoices = $invoices->paginate($request->per_page);

        // view
        return view('admin.invoices.list', [
            'invoices' => $invoices,
        ]);
    }

    public function transactionList(Request $request)
    {
        $transactions = Transaction::select('transactions.*')
            ->leftJoin('invoices', 'invoices.id', '=', 'transactions.invoice_id');

        // transaction filter
        if (isset($request->customer_uid)) {
            $customer = Customer::findByUid($request->customer_uid);
            $transactions = $transactions->where('invoices.customer_id', $customer->id);
        }

        // status filter
        if (isset($request->status)) {
            $transactions = $transactions->where('transactions.status', $request->status);
        }

        // sort
        if (!empty($request->sort_order)) {
            $transactions = $transactions->orderBy($request->sort_order, $request->sort_direction);
        }

        // pagination
        $transactions = $transactions->paginate($request->per_page);

        // view
        return view('admin.invoices.transactionList', [
            'transactions' => $transactions,
        ]);
    }

    public function delete(Request $request)
    {
        // init
        $invoice = Invoice::findByUid($request->invoice_uid);

        if ($request->user()->admin->can('delete', $invoice)) {
            $invoice->cancel();
        }

        echo trans('messages.invoice.deleted');
    }

    public function approve(Request $request)
    {
        $invoice = Invoice::findByUid($request->invoice_uid);

        // authorize
        if (!$request->user()->admin->can('approve', $invoice)) {
            return $this->notAuthorized();
        }

        // try {
        // approve invoice
        $invoice->approve();
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $e->getMessage(),
        //     ]);
        // }

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.invoice.approve.success'),
        ]);
    }

    public function reject(Request $request)
    {
        // init
        $invoice = Invoice::findByUid($request->invoice_uid);

        // authorize
        if (!$request->user()->admin->can('reject', $invoice)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $validator = \Validator::make($request->all(), ['reason' => 'required']);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.invoices.reject', [
                    'invoice' => $invoice,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // try reject
            try {
                $invoice->reject($request->reason);
            } catch (\Throwable $ex) {
                $validator->errors()->add('reason', $ex->getMessage());

                return response()->view('admin.invoices.reject', [
                    'invoice' => $invoice,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // success
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.invoice.reject.success'),
            ]);
        }

        return view('admin.invoices.reject', [
            'invoice' => $invoice,
        ]);
    }

    public function logs(Request $request)
    {
        // init
        $invoice = Invoice::findByUid($request->invoice_uid);

        return view('admin.invoices.logs', [
            'invoice' => $invoice,
        ]);
    }

    public function customCreate(Request $request)
    {
        $invoice = Invoice::newDefault();

        return view('admin.invoices.custom.create', [
            'invoice' => $invoice,
        ]);
    }

    public function customStore(Request $request)
    {
        $invoice = Invoice::newDefault();

        $validator = \Validator::make($request->all(), [
            'customer_uid' => 'required',
            'currency_uid' => 'required',
            'amount' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        // redirect if fails
        if ($validator->fails()) {
            return response()->view('admin.invoices.custom.create', [
                'invoice' => $invoice,
                'errors' => $validator->errors(),
            ], 400);
        }

        // find customer
        $customer = \Acelle\Model\Customer::findByUid($request->customer_uid);
        $currency = \Acelle\Model\Currency::findByUid($request->currency_uid);

        $customer->createCustomInvoice($request->amount, $currency, $request->title, $request->description);

        return redirect()->action('Admin\InvoiceController@index')
            ->with('alert-success', 'Custom invoice was created!');
    }

    public function invoicesListing(Request $request)
    {
        //dd($request);
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($request->ajax()) {
            $invoices = NewInvoice::query();

            // Search functionality
            if ($request->has('search') && $request->search['value']) {
                $searchTerm = $request->search['value'];
                $invoices = $invoices->where('uid', 'like', '%' . $searchTerm . '%')
                    ->orWhere('invoice_number', 'like', '%' . $searchTerm . '%');
            }

            // Sorting functionality
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir']; // 'asc' or 'desc'

                $columns = ['uid', 'date_time', 'grand_total', 'payment_status', 'status'];

                if (isset($columns[$orderColumnIndex])) {
                    $invoices = $invoices->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            }

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->editColumn('uid', function ($row) {
                    return 'Customer ' . $row->uid;
                })
                ->editColumn('date_time', function ($row) {
                    return date('Y-m-d', strtotime($row->date_time));
                })
                ->editColumn('grand_total', function ($row) {
                    return number_format($row->grand_total, 2);
                })
                ->editColumn('payment_status', function ($row) {
                    return $row->payment_status == 1 ? 'Paid' : 'Pending';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? 'Assigned' : 'Not Assigned';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="#" class="btn btn-primary btn-sm"><i class="fas fa-eye" title="View Details"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.invoices.invoices_listing');
    }
}
