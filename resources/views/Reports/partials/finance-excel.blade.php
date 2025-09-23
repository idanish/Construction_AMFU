<?php

namespace App\Exports;

use App\Models\Budget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinanceReportExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Budget::with('department')
            ->when($this->request->status, function ($q) {
                $q->where('status', $this->request->status);
            });

        return $query->get()->map(function ($item) {
            return [
                'Department' => $item->department->name ?? '-',
                'Allocated'  => $item->allocated,
                'Spent'      => $item->spent,
                'Balance'    => $item->balance,
                'Status'     => $item->status,
                'Transaction'=> $item->transaction_no,
                'Created At' => $item->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Department',
            'Allocated',
            'Spent',
            'Balance',
            'Status',
            'Transaction',
            'Created At'
        ];
    }
}
