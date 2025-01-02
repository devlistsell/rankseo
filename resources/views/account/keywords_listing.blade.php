@extends('layouts.core.frontend_no_subscription', [
    'menu' => 'keywords',
])

@section('title', trans('messages.keywords_listing'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">    
@endsection

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.keywords') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-keywords"></i> {{ trans('messages.keywords_listing') }}</span>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <table id="keywords-table" class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                     <th>{{ trans('messages.keyword') }}</th>
                    <th>{{ trans('messages.ranking') }}</th>
                    <th>{{ trans('messages.difficulty') }}</th>
                    <th>{{ trans('messages.date') }}</th>
                    <th>{{ trans('messages.time') }}</th>
                    <th>{{ trans('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

   <script type="text/javascript">
    $(function () {
        var table = $('#keywords-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('keywords.listing') }}',
                type: 'GET',
            },
            columns: [
    { data: 'keyword', name: 'keyword' },
    { data: 'ranking', name: 'ranking' },
    { data: 'difficulty', name: 'difficulty' },
    { data: 'date', name: 'date_time' }, // Update 'date' to 'date_time'
    { data: 'time', name: 'date_time' }, // Update 'time' to 'date_time'
    { data: 'action', name: 'action', orderable: false, searchable: false },
],
            searching: true,
        });
    });
</script>

@endsection