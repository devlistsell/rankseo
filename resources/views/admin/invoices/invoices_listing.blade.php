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
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal" id="assignStatusModal" tabindex="-1" aria-labelledby="assignStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignStatusModalLabel">Confirm Status Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to assign this invoice?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmChange">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <script type="text/javascript">
    $(function () {
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
            searching: true,
        });

        $(document).on('click', '.assign-btn', function() {
            var invoiceId = $(this).data('id');
            var invoiceName = $(this).data('name');

            $('#assignStatusModal').data('invoice-id', invoiceId);
            $('#assignStatusModalLabel').text('Assign Invoice: ' + invoiceName);

            $('#assignStatusModal').modal('show');
        });

        $('#confirmChange').on('click', function() {
            var invoiceId = $('#assignStatusModal').data('invoice-id');
            
            $.ajax({
                url: '{{ route("invoices.assign") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    invoice_id: invoiceId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);  // Show success message
                        $('#assignStatusModal').modal('hide');
                        $('#invoices-table').DataTable().ajax.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while assigning the invoice.');
                }
            });
        });
    });
    </script> -->
    <script type="text/javascript">
$(function () {
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
            // { data: 'status', name: 'invoice_clients.status', render: function(data) {
            //     return data === 1 ? 'Assigned' : 'Not Assigned';
            // }},
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false 
            },
        ],
        searching: true,
    });

    // Handle "Assign Invoice" button click
    $(document).on('click', '.assign-btn', function() {
        var invoiceId = $(this).data('id');
        var assignButton = $(this);

        if (confirm('Are you sure you want to assign this invoice?')) {
            $.ajax({
                url: '{{ route("invoices.assign") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    invoice_id: invoiceId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);

                        // Reload the DataTable to reflect the updated status
                        table.ajax.reload(null, false);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while assigning the invoice.');
                }
            });
        }
    });
});
</script>
@endsection
