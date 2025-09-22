<?php

namespace App\Exports;

use App\Models\Procurement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProcurementReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Procurement::with('department')
            ->get()
            ->map(function ($procurement) {
                return [
                    'Item Name'     => $procurement->item_name,
                    'Quantity'      => $procurement->quantity,
                    'Cost Estimate' => $procurement->cost_estimate,
                    'Department'    => optional($procurement->department)->name ?? 'N/A',
                    'Remarks' => $procurement->justification ?? '-',
                    'Date'    => $procurement->created_at->format('d-M-Y h:i A'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Quantity',
            'Cost Estimate',
            'Department',
            'Remarks',
            'Date',
        ];
    }
}
