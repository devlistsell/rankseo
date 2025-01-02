@extends('layouts.core.frontend_no_subscription', [
    'menu' => 'keywords',
])

@section('title', trans('messages.keyword_history'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.keyword_history') }}</li>
        </ul>
         <h1>
            <span class="text-semibold">
                <i class="icon-keywords"></i> 
                {{ $keywordName ? 'Keyword ' . $keywordName : trans('messages.keyword') }}
            </span>
        </h1>
        <a href="{{ route('keywords.listing') }}" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Back to Keywords
        </a>
    </div>
@endsection

@section('content')
    <div class="d-flex top-list-controls top-sticky-content">
        <div class="me-auto">
            <div class="filter-box">
                <span class="filter-group">
                    <span class="title text-semibold text-muted">{{ trans('messages.filter_by_date') }}:</span>
                    <input type="text" name="daterange" value="" class="form-control" />
                </span>
                <span class="filter-group">
                    <span class="title text-semibold text-muted">{{ trans('messages.filter_by_ranking') }}:</span>
                    <input type="text" id="ranking-search" class="form-control" placeholder="{{ trans('messages.enter_ranking') }}" />
                </span>
                <button type="button" id="filter-btn" class="btn btn-primary">{{ trans('messages.filter') }}</button>
                <button type="button" id="clear-btn" class="btn btn-secondary">{{ trans('messages.clear_filter') }}</button>
            </div>
        </div>
    </div>

    <div class="row">
        <table id="keywords-table" class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    <!-- <th>{{ trans('messages.keyword') }}</th> -->
                    <th>{{ trans('messages.ranking') }}</th>
                    <th>{{ trans('messages.date') }}</th>
                    <th>{{ trans('messages.time') }}</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(function() {
        var daterangePicker = $('input[name="daterange"]').daterangepicker({
            startDate: moment().subtract(1, 'months'),
            endDate: moment(),
            locale: {
                format: 'MM/DD/YYYY'  
            },
            autoUpdateInput: false,
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        var table = $('#keywords-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('allKeywordsHistory') }}',
                data: function(d) {
                    var daterange = $('input[name="daterange"]').data('daterangepicker');

                    if (daterange && daterange.startDate && daterange.endDate) {
                        d.from_date = daterange.startDate.format('YYYY-MM-DD');
                        d.to_date = daterange.endDate.format('YYYY-MM-DD');
                    } else {
                        d.from_date = '';
                        d.to_date = '';
                    }

                    d.keyword_name = '{{ request('keyword_name') }}';

                    d.ranking_search = $('#ranking-search').val(); 
                }
            },
            columns: [
                { data: 'ranking', name: 'ranking' },
                { data: 'date', name: 'date' },
                { data: 'time', name: 'time' },
            ],
            searching: false,
            language: {
                emptyTable: "{{ trans('messages.no_data_found') }}",
            },
        });

        $('#filter-btn').click(function() {
            table.ajax.reload();
        });

        $('#clear-btn').click(function() {
            $('input[name="daterange"]').val('');
            daterangePicker.data('daterangepicker').setStartDate(moment().subtract(1, 'months'));
            daterangePicker.data('daterangepicker').setEndDate(moment());
            
            table.ajax.reload();
        });
    });
    </script>
@endsection
