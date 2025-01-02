<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Acelle\Model\NewInvoice;
use DataTables;

class NewInvoiceController extends Controller
{
    public function invoicesListing(Request $request)
    {
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
