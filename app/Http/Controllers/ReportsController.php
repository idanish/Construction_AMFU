<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;

class ReportsController extends Controller
{
    // Finance Report Page
    public function financeReport(Request $request)
    {
        // filters (status, department, date range, etc)
        $status = $request->input('status');
        $department = $request->input('department_id');

        $budgets = Budget::with('department')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($department, fn($q) => $q->where('department_id', $department))
            ->paginate($request->input('per_page', 25));

        return view('Reports.finance-report', compact('budgets'));
    }

    
}
