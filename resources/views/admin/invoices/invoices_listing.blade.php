@extends('layouts.core.backend', [
    'menu' => 'invoices',
])

@section('title', trans('messages.invoices_listing'))

@section('head')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action('HomeController@index') }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.invoices') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-invoices"></i> {{ trans('messages.invoices_listing') }}</span>
        </h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <table id="invoices-table" class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    {{--<th>{{ trans('messages.customer_name') }}</th>--}}
                    <th>{{ trans('messages.invoice_number') }}</th>
                    <th>{{ trans('messages.generate_date') }}</th>
                    <th>{{ trans('messages.grand_total') }}</th>
                    <th>{{ trans('messages.payment_status') }}</th>
                    <th>{{ trans('messages.assign_status') }}</th>
                    <th>{{ trans('messages.action') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(function () {
            var table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('invoices.listing') }}',
                columns: [
                    {{--{ data: 'uid', name: 'uid' },--}}
                    { data: 'invoice_number', name: 'invoice_number' },
                    { data: 'date_time', name: 'date_time' },
                    { data: 'grand_total', name: 'grand_total' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                searching: true,
            });
        });
    </script>
@endsection
