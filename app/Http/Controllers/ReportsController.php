<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceReportExport;
use PDF;

class ReportsController extends Controller
{
    public function financeReport(Request $request)
    {
        // Filters
        $status     = $request->input('status');
        $fromDate   = $request->input('from_date');
        $toDate     = $request->input('to_date');
        $perPage    = $request->input('per_page', 25);

        $query = Budget::with('department')
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($fromDate, function ($q) use ($fromDate) {
                $q->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($q) use ($toDate) {
                $q->whereDate('created_at', '<=', $toDate);
            });

        $budgets = $query->paginate($perPage);

        return view('reports.finance-report', compact('budgets'));
    }

    public function exportFinanceExcel(Request $request)
    {
        return Excel::download(new FinanceReportExport($request), 'finance_report.xlsx');
    }

    public function exportFinancePdf(Request $request)
    {
        $budgets = Budget::with('department')->get();
        $pdf = PDF::loadView('reports.partials.finance-pdf', compact('budgets'));
        return $pdf->download('finance_report.pdf');
    }
}
