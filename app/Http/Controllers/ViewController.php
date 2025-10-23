<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Procurement;
use App\Models\Department;
use App\Models\RequestModel;

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
        // Top Cards
        $totalBudgets = Budget::sum('allocated');
        $totalInvoices = Invoice::count();
        $totalPayments = Payment::sum('amount');
        $totalProcurements = Procurement::count();


        // Pending Request
        $pendingRequest = RequestModel::where('status', 'pending')->take(5)->get();
        $pendingInvoices = Invoice::where('status', 'unpaid')->take(5)->get();
        $pendingProcurement = Procurement::where('status', 'pending')->take(5)->get();
        $pendingBudget = Budget::where('status', 'pending')->take(5)->get();

        return view('Admin.dashboard', compact(
            'totalBudgets',
            'totalInvoices',
            'totalPayments',
            'totalProcurements',
            'pendingRequest','pendingInvoices', 'pendingProcurement', 'pendingBudget'
        ));
    }

}