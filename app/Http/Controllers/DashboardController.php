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
        // Top Cards
        $totalBudgets = Budget::sum('amount');
        $totalInvoices = Invoice::count();
        $monthlyPayments = Payment::whereMonth('payment_date', now()->month)->sum('amount'); // rename
        $totalProcurements = Procurement::count(); // rename

        // Latest Payments with pagination
        $latestPayments = Payment::latest()->paginate(10);

        // Procurement Snapshot
        $procurements = Procurement::with('department')->latest()->take(5)->get();

        // Alerts
        $unpaidInvoices = Invoice::where('status', 'unpaid')->whereDate('due_date', '<=', now()->addDays(7))->get();

        $pendingProcurements = Procurement::where('status', 'pending')->take(5)->get();

        $unpaidPayments = Payment::where('status', 'unpaid')
            ->whereDate('payment_date', '<=', now()->addDays(7))
            ->get();

        // 🔹 Chart Data
        $monthlyRevenues = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyExpenses = Invoice::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('dashboard', compact(
            'totalBudgets',
            'totalInvoices',
            'monthlyPayments',
            'totalProcurements',
            'latestPayments',
            'procurements',
            'unpaidInvoices',
            'pendingProcurements',
            'unpaidPayments',
            'monthlyRevenues',
            'monthlyExpenses'
        ));
    }
}
