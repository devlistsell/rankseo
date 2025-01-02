@extends('layouts.core.backend', [
    'menu' => 'customer',
])

@section('title', trans('messages.invoice'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action('Admin\HomeController@index') }}">{{ trans('messages.home') }}</a>
            </li>
            <li class="breadcrumb-item"><a
                    href="{{ action('Admin\CustomerController@index') }}">{{ trans('messages.customers') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.invoice') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-address-book3"></i> {{ $contact->company }}
                ({{ $contact->name($customer->getLanguageCode()) }})</span>
        </h1>
    </div>

@endsection

@section('content')

    @include('admin.customers._tabs')
    <div class="d-flex top-list-controls top-sticky-content">
        <div class="me-auto">
            <h3 class="text-semibold text-primary">Invoice Management</h3>
        </div>
        <div class="text-end">
            <button role="button" class="btn btn-info generateInvoiceBtn">
                <span class="material-symbols-rounded">add</span> Generate New Invoice
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice No</th>
                        <th scope="col">Total Keywords</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">DateTime</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col"> Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($invoices))
                        @foreach ($invoices as $key => $row)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $row->invoice_number }}</td>
                                <td>3</td>
                                <td>{{ $row->grand_total }}</td>
                                <td>{{ date('d-m-Y', strtotime($row->date_time)) }},{{ date('H:i:s', strtotime($row->date_time)) }}

                                <td>
                                    @if ($row->payment_status == 1)
                                        Paid
                                    @elseif ($row->payment_status == 2)
                                        Pendind
                                    @elseif ($row->payment_status == 3)
                                        Rejected
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>
                                    @if ($row->status == 0)
                                        Not Assign
                                    @elseif ($row->status == 1)
                                        Assign
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>
            <div class="pagination-container">
                {{ $invoices->links('pagination::bootstrap-4') }}
            </div>
            @if (count($invoices) == 0)
                <div class="pml-table-container">
                    <div class="empty-list">
                        <i class="material-symbols-rounded">assignment_turned_in</i>
                        <span class="line-1">
                            There is no invoice result
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Button trigger modal -->
    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Launch demo modal
    </button> --}}

    <!-- Modal -->
    <div class="modal fade" id="generateInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate Invoice</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group control-text">
                                <label> Name </label>
                                <input id="plan[general][name]" placeholder="" value="" type="text"
                                    name="plan[general][name]" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label class="form-label mb-0">
                                    <span class="form-label d-flex align-items-center">
                                        <label>
                                            <input type="checkbox" class="styled me-2 numeric" name="has_trial"
                                                value="yes"><span class="check-symbol"></span>
                                        </label>

                                        <span class="ms-2">Enable Invoice</span>

                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary createInvoice">Create Invoice</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <hr> --}}
    <script>
        $(document).ready(function() {
            $(".generateInvoiceBtn").click(function() {
                $('#generateInvoice').modal('show');
            });
            $(".createInvoice").click(function() {
                alert();
            });
            //Generate Invoice Section
            $(".createInvoice").click(function() {
                $.ajax({
                    url: "{{ route('admin.create_invoice') }}",
                    type: "POST",
                    data: {
                        client_id: "{{ $customer->id }}",
                        // ranking: $('#rank').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == false) {
                            $('.keyword_exist_error').removeClass('hide');
                        } else if (response.status == true) {
                            location.reload();
                        } else {
                            $("#error-message").text(response.message).show();
                            $("#success-message").hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#error-message").text('Something went wrong!').show();
                    }
                });
            });
        });
    </script>
@endsection
