@extends('layouts.core.frontend_no_subscription', [
    'menu' => 'keywords',
])

@section('title', trans('messages.keyword_history_listing'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item active">{{ trans('messages.keyword_history') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-keywords"></i> {{ trans('messages.keyword_history_listing') }}</span>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        @if ($keyword_histories->count() > 0)
            <table class="table table-bordered table-striped mt-2">
    <thead>
        <tr>
            <th>{{ trans('messages.keyword') }}</th>
            <th>{{ trans('messages.ranking') }}</th>
            <th>{{ trans('messages.date_time') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($keyword_histories as $history)
            <tr>
                <td>{{ $history->keyword->keyword }}</td>
                <td>{{ $history->ranking }}</td>
                <td>{{ $history->date_time }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $keyword_histories->links() }}
        @else
            <div class="empty-list">
                <span class="material-symbols-rounded">search_off</span>
                <span class="line-1">{{ trans('messages.no_keyword_history_found') }}</span>
            </div>
        @endif
    </div>
@endsection
