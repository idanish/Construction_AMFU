<?php

namespace App\Exports;

use App\Models\Budget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinanceReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    // ✅ Filters ko constructor se pass karenge (agar zaroorat ho)
    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Budget::query();

        // ✅ Example: filter by department
        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        // ✅ Example: filter by status
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->get();
    }

    /**
     * Exported file ke headings (Excel ke upar wali row)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Requestor ID',
            'Department ID',
            'Description',
            'Amount',
            'Comments',
            'Status',
            'Created At',
        ];
    }

    /**
     * Har row ko format karke export karna
     */
    public function map($budget): array
    {
        return [
            $budget->id,
            $budget->requestor_id,
            $budget->department_id,
            $budget->description,
            $budget->amount,
            $budget->comments,
            ucfirst($budget->status),
            $budget->created_at->format('Y-m-d'),
        ];
    }
}
