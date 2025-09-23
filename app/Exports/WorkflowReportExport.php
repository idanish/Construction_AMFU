<?php
namespace App\Exports;

use App\Models\Workflow;
use Maatwebsite\Excel\Concerns\FromCollection;

class WorkflowReportExport implements FromCollection
{
    public function collection()
    {
        return Workflow::with(['createdBy', 'department'])->get();
    }
}
