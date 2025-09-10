<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function download($id) {
    $report = Report::findOrFail($id);

    // user apna report download kare
    createNotification(null, "You downloaded report: {$report->name}", auth()->id(), 'download');

    // admin download kare
    if (auth()->user()->hasRole('Admin')) {
        createNotification('Admin', "Admin ".auth()->user()->name." downloaded report: {$report->name}");
    }

    // actual download logic...
}

}
