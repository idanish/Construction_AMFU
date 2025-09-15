<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportsController extends Controller
{
    // Audit Report
    public function auditReport()
    {
        return view('Reports.audit-report');
    }

    // Finance Report
    public function financeReport()
    {
        return view('Reports.finance-report');
    }

    // Procurement Analysis
    public function procurementAnalysis()
    {
        return view('Reports.procurement-analysis');
    }

    // Request Report
    public function requestReport()
    {
        return view('Reports.request-report');
    }

    // Work Flow Report
    public function workFlowReport()
    {
        return view('Reports.work-flow-report');
    }
}

