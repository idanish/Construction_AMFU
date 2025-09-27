<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Procurement;
use App\Models\Payment;
use App\Models\RequestModel;

class RequestApproval extends Controller
{
    public function pending()
    {
        
        $pendingRequest = RequestModel::where('status', 'pending')->get();
        $pendingInvoices = Invoice::where('status', 'unpaid')->get();
        $pendingProcurement = Procurement::where('status', 'pending')->get();
        $pendingBudget = Budget::where('status', 'pending')->get();

        return view('requests.pending_request', compact('pendingRequest','pendingInvoices', 'pendingProcurement', 'pendingBudget'));
    }

    public function rejected()
    {
       
        $rejectedRequest = RequestModel::where('status', 'rejected')->get();
        $rejectedProcurement = Procurement::where('status', 'rejected')->get();
        $rejectedBudget = Budget::where('status', 'rejected')->get();

        return view('requests.rejected_request', compact('rejectedRequest', 'rejectedProcurement', 'rejectedBudget'));
    }

}
