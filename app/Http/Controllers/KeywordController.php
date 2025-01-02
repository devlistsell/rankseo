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

    if ($request->ajax()) {
        $keywords = $user->keywords();

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $searchTerm = $request->search['value'];
            $keywords = $keywords->where('keyword', 'like', '%' . $searchTerm . '%');
        }

        // Sorting functionality
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir']; // 'asc' or 'desc'

            $columns = ['keyword', 'ranking', 'difficulty', 'date_time']; // Match DataTables order

            if (isset($columns[$orderColumnIndex])) {
        $keywords = $keywords->orderBy($columns[$orderColumnIndex], $orderDirection);
    }
        }

        return DataTables::of($keywords)
            ->addIndexColumn()
            ->editColumn('keyword', function ($row) {
                return $row->keyword;
            })
            ->editColumn('ranking', function ($row) {
                return $row->ranking;
            })
            ->editColumn('difficulty', function ($row) {
                if ($row->difficulty_id == 1) {
                    $difficulty = '0-49';
                } elseif ($row->difficulty_id == 2) {
                    $difficulty = '50-69';
                } elseif ($row->difficulty_id == 3) {
                    $difficulty = '70+';
                } else {
                    $difficulty = '--';
                }
                return $difficulty;
            })
            ->editColumn('date', function ($row) {
                return $row->formatDate();
            })
            ->editColumn('time', function ($row) {
                return $row->formatTime();
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('allKeywordsHistory', ['keyword_name' => $row->keyword]) . '" class="btn btn-primary btn-sm"><i class="fas fa-eye" title="view keyword history"></i></a>';
            })
            ->rawColumns(['keyword', 'ranking', 'difficulty', 'date', 'time', 'action'])
            ->make(true);
    }

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

            $keywordName = $request->keyword_name;

            if ($request->ajax()) {
                $history = KeywordHistory::with('keyword')
                    ->whereIn('keyword_id', $user->keywords()->pluck('id'));

            if ($request->filled('keyword_name')) {
                $keywordName = $request->keyword_name;
                $history = $history->whereHas('keyword', function ($query) use ($keywordName) {
                    $query->where('keyword', $keywordName);
                });
            }

            if ($request->filled('ranking_search')) {
                $rankingSearch = $request->ranking_search;
                $history = $history->where('ranking', 'like', '%' . $rankingSearch . '%');
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $fromDate = Carbon::parse($request->from_date)->startOfDay();
                $toDate = Carbon::parse($request->to_date)->endOfDay();
                $history = $history->whereBetween('date_time', [$fromDate, $toDate]);
            }

            return DataTables::of($history)
                ->addIndexColumn()
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
            'keywordName' => $keywordName,
        ]);
    }

}
