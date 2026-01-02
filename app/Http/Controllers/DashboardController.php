<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Procurement;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // Top Cards
    $totalBudgets = Budget::sum('amount');
    $totalInvoices = Invoice::count();
    $monthlyPayments = Payment::whereMonth('payment_date', now()->month)->sum('amount');
    $totalProcurements = Procurement::count();
    
    // Pending Budgets
    $pendingBudget = Budget::with('department')->where('status', 'pending')->latest()->take(5)->get();

    // Unpaid/Pending Invoices
    $pendingInvoices = Invoice::where('status', 'unpaid')->latest()->take(5)->get();

    // Pending Requests
    $user = auth()->user();

$pendingRequest = RequestModel::where('status', 'pending')
    ->when(!$user->hasRole('super-admin'), function($q) use ($user) {
        return $q->where('current_level', optional($user->approvalLevel)->sequence)
                 ->where('department_id', $user->department_id);
    })
    ->latest()
    ->take(5)
    ->get();

    return view('dashboard', compact(
        'totalBudgets', 'totalInvoices', 'monthlyPayments', 'totalProcurements',
        'pendingBudget', 'pendingInvoices', 'pendingRequest'
    ));
}
}