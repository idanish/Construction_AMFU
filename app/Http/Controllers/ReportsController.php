<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function auditReport(Request $request)
    {
        // Activity logs fetch + filters
    }

    public function financeReport(Request $request)
    {
        // Budget + Invoice + Payment ka data
    }

    public function procurementAnalysis(Request $request)
    {
        // Procurement ka data + filters
    }

    public function requestReport(Request $request)
    {
        // Requests table ka data + filters
    }

    public function workflowReport(Request $request)
    {
        // Request → Procurement → Invoice → Payment ka joined data
    }
}
