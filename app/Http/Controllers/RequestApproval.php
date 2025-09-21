<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Procurement;
use App\Models\Payment;

class RequestApproval extends Controller
{
    public function pending()
    {
        
        $pendingPayments = Payment::where('status', 'pending')->get();
        $pendingInvoices = Invoice::where('status', 'pending')->get();
        $pendingProcurement = Procurement::where('status', 'pending')->get();
        $pendingBudget = Budget::where('status', 'pending')->get();

        return view('requests.pending_request', compact('pendingPayments', 'pendingInvoices', 'pendingProcurement', 'pendingBudget'));
    }

    public function rejected()
    {
       
        $rejectedPayments = Payment::where('status', 'rejected')->get();
        $rejectedInvoices = Invoice::where('status', 'rejected')->get();
        $rejectedProcurement = Procurement::where('status', 'rejected')->get();
        $rejectedBudget = Budget::where('status', 'rejected')->get();

        return view('requests.rejected_request', compact('rejectedPayments', 'rejectedInvoices', 'rejectedProcurement', 'rejectedBudget'));
    }

}
