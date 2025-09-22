<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Procurement;

class ViewController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function users()
    {
        return view('auth-user');
    }

    public function form()
    {   
        return view('form');
    }

    // Admin Dashboard

    public function adminDashboard()
    {
        // Example values (aap apne hisaab se adjust karenge)
        $totalBudgets = Budget::count();
        $totalInvoices = Invoice::count();
        $totalPayments = Payment::sum('amount');
        $totalProcurements = Procurement::count();

        // Agar aapko monthly bhi chahiye
        $monthlyPayments = Payment::whereMonth('created_at', now()->month)->sum('amount');

        return view('Admin.dashboard', compact(
            'totalBudgets',
            'totalInvoices',
            'totalPayments',
            'monthlyPayments',
            'totalProcurements'
        ));
    }
}


