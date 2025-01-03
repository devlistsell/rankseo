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
                    <th>{{ trans('messages.customer_name') }}</th>
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
                { data: 'uid', name: 'users.first_name' }, // Sorting by user's first name
                { data: 'invoice_number', name: 'invoice_clients.invoice_number' },
                { data: 'date_time', name: 'invoice_clients.date_time' },
                { data: 'grand_total', name: 'invoice_clients.grand_total' },
                { data: 'payment_status', name: 'invoice_clients.payment_status' },
                { data: 'status', name: 'invoice_clients.status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            searching: true,
        });
    });
</script>

{{--<script type="text/javascript">
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('#invoices-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('invoices.listing') }}',
            columns: [
                { data: 'uid', name: 'users.first_name' },
                { data: 'invoice_number', name: 'invoice_clients.invoice_number' },
                { data: 'date_time', name: 'invoice_clients.date_time' },
                { data: 'grand_total', name: 'invoice_clients.grand_total' },
                { data: 'payment_status', name: 'invoice_clients.payment_status' },
                { data: 'status', name: 'invoice_clients.status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        // Handle Assign Button Click
        $('#invoices-table').on('click', '.assign-btn', function () {
            const invoiceId = $(this).data('id');
            $('#invoiceId').val(invoiceId);
            $('#assignModal').modal('show');
        });

        // Load Clients into Dropdown
        $('#assignModal').on('show.bs.modal', function () {
            $('#clientSelect').select2({
                dropdownParent: $('#assignModal'),
                ajax: {
                    url: '{{ route("clients.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return { id: item.id, text: item.first_name + ' ' + item.last_name };
                            }),
                        };
                    },
                },
            });
        });

        // Handle Form Submission
        $('#assignForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '{{ route("invoices.assign") }}',
                type: 'POST',
                data: formData,
                success: function (response) {
                    $('#assignModal').modal('hide');
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function (xhr) {
                    alert('An error occurred. Please try again.');
                },
            });
        });
    });
</script>--}}


@endsection
