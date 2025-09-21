<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;

class AuditReportExport implements FromCollection
{
    public function collection()
    {
        return Payment::with(['invoice', 'invoice.request'])->get();
    }
}

