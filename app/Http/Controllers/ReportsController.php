<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\FinanceReportExport;
use App\Exports\AuditReportExport;
use App\Exports\ProcurementReportExport;
use App\Exports\RequestReportExport;
use App\Exports\WorkflowReportExport;

class ReportsController extends Controller
{
    // FINANCE REPORTS SECTION
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
        $pdf = PDF::loadView('reports.exports.finance-pdf', compact('budgets'));
        return $pdf->download('finance_report.pdf');
    }



    // AUDIT REPORT SECTION
    public function auditReport(Request $request)
    {
        $query = Payment::with(['invoice', 'invoice.request']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        }

        // Pagination (25, 50, 100)
        $payments = $query->paginate($request->get('per_page', 25));

        return view('reports.audit-report', compact('payments'));
    }

    public function exportAuditExcel()
    {
        return Excel::download(new AuditReportExport, 'audit_report.xlsx');
    }

    public function exportAuditPDF()
    {
        $payments = Payment::with(['invoice', 'invoice.request'])->get();
        $pdf = Pdf::loadView('reports.exports.audit-pdf', compact('payments'));
        return $pdf->download('audit_report.pdf');
    }

    // PROCUREMENT REPORT SECTION
public function procurementReport(Request $request)
{
    $query = \App\Models\Procurement::with(['supplier', 'items']);

    // Filters
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('date_from') && $request->filled('date_to')) {
        $query->whereBetween('procurement_date', [$request->date_from, $request->date_to]);
    }

    // Pagination
    $procurements = $query->paginate($request->get('per_page', 25));

    return view('reports.procurement-report', compact('procurements'));
}

public function exportProcurementExcel()
{
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProcurementReportExport, 'procurement_report.xlsx');
}

public function exportProcurementPDF()
{
    $procurements = \App\Models\Procurement::with(['supplier', 'items'])->get();
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.procurement-pdf', compact('procurements'));
    return $pdf->download('procurement_report.pdf');
}


// REQUEST REPORT SECTION

public function requestReport(Request $request)
{
    $query = \App\Models\RequestModel::with(['user', 'department']);

    // Filters
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }
    if ($request->filled('date_from') && $request->filled('date_to')) {
        $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
    }

    $requests = $query->paginate($request->get('per_page', 25));

    return view('reports.request-report', compact('requests'));
}

public function exportRequestExcel()
{
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RequestReportExport, 'request_report.xlsx');
}

public function exportRequestPDF()
{
    $requests = \App\Models\RequestModel::with(['user', 'department'])->get();
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.request-pdf', compact('requests'));
    return $pdf->download('request_report.pdf');
}


// WORKFLOW REPORT SECTION

public function workflowReport(Request $request)
{
    $query = \App\Models\Workflow::with(['createdBy', 'department']);

    // Filters
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }
    if ($request->filled('date_from') && $request->filled('date_to')) {
        $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
    }

    $workflows = $query->paginate($request->get('per_page', 25));

    return view('reports.workflow-report', compact('workflows'));
}

public function exportWorkflowExcel()
{
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\WorkflowReportExport, 'workflow_report.xlsx');
}

public function exportWorkflowPDF()
{
    $workflows = \App\Models\Workflow::with(['createdBy', 'department'])->get();
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.exports.workflow-pdf', compact('workflows'));
    return $pdf->download('workflow_report.pdf');
}

}
