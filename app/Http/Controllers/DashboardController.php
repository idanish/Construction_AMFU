<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
{
    // Top Cards
    $totalBudgets = \App\Models\Budget::sum('amount');
    $totalInvoices = \App\Models\Invoice::count();
    $totalPayments = \App\Models\Payment::whereMonth('payment_date', now()->month)->sum('amount');
    $activeProcurements = \App\Models\Procurement::where('status', 'running')->count();

    // Recent Transactions
    $latestInvoices = \App\Models\Invoice::latest()->take(5)->get();
    $latestPayments = \App\Models\Payment::latest()->take(5)->get();

    // Procurement Snapshot
    $procurements = \App\Models\Procurement::with('department')->latest()->take(5)->get();

    // Alerts
    $unpaidInvoices = \App\Models\Invoice::where('status', 'unpaid')
        ->whereDate('due_date', '<=', now()->addDays(7))
        ->get();

    $pendingProcurements = \App\Models\Procurement::where('status', 'pending')->take(5)->get();

    return view('dashboard', compact(
        'totalBudgets',
        'totalInvoices',
        'totalPayments',
        'activeProcurements',
        'latestInvoices',
        'latestPayments',
        'procurements',
        'unpaidInvoices',
        'pendingProcurements'
    ));
}

}
