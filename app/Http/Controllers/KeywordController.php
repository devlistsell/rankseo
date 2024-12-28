<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Acelle\Model\Keyword;
use Acelle\Model\KeywordHistory;
use Carbon\Carbon;
use DataTables;

class KeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function keywordsListing(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!Gate::allows('viewKeywords', $user->customer)) {
            return abort(403, trans('messages.not_authorized'));
        }

        // Handle AJAX request for DataTables
        if ($request->ajax()) {
            $keywords = $user->keywords();

            // Apply global search filter (search term)
            if ($request->has('search') && $request->search['value']) {
                $searchTerm = $request->search['value'];
                $keywords = $keywords->where('keyword', 'like', '%' . $searchTerm . '%');
            }

            return DataTables::of($keywords)
                ->addIndexColumn()
                ->editColumn('keyword', function ($row) {
                    return $row->keyword; // Assuming the 'keyword' column is directly accessible
                })
                ->editColumn('ranking', function ($row) {
                    return $row->ranking;
                })
                ->editColumn('difficulty', function ($row) {
                    return $row->difficulty;
                })
                ->editColumn('date', function ($row) {
                    return $row->formatDate(); // Use the `formatDate` method for display
                })
                ->editColumn('time', function ($row) {
                    return $row->formatTime(); // Use the `formatTime` method for display
                })
                ->rawColumns(['keyword', 'ranking', 'difficulty', 'date', 'time'])
                ->make(true);
        }

        // Render the regular view
        return view('account.keywords_listing', [
            'user' => $user,
        ]);
    }
    
    public function allKeywordsHistory(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!Gate::allows('viewKeywords', $user->customer)) {
            return abort(403, trans('messages.not_authorized'));
        }

        // If it's an AJAX request (for DataTables)
        if ($request->ajax()) {
            $history = KeywordHistory::with('keyword')
                ->whereIn('keyword_id', $user->keywords()->pluck('id'));

            // Apply date range filter if 'from_date' and 'to_date' are present in the request
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $fromDate = Carbon::parse($request->from_date)->startOfDay();
                $toDate = Carbon::parse($request->to_date)->endOfDay();
                $history = $history->whereBetween('date_time', [$fromDate, $toDate]);
            }

            // Apply global search filter (search term)
            if ($request->has('search') && $request->search['value']) {
                $searchTerm = $request->search['value'];
                $history = $history->whereHas('keyword', function ($query) use ($searchTerm) {
                    $query->where('keyword', 'like', '%' . $searchTerm . '%');
                });
            }

            return DataTables::of($history)
                ->addIndexColumn()
                ->addColumn('keyword', function ($row) {
                    return $row->keyword->keyword;
                })
                ->addColumn('ranking', function ($row) {
                    return $row->ranking;
                })
                ->addColumn('date', function ($row) {
                    return $row->formatDate();
                })
                ->addColumn('time', function ($row) {
                    return $row->formatTime();
                })
                ->make(true);
        }

        return view('account.all_keyword_history', [
            'user' => $user,
        ]);
    }
}
