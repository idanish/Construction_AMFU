<?php

namespace App\Listeners;

use App\Events\ProcurementApproved;
use App\Models\Budget;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateBudgetAndInvoice
{
    public function handle(ProcurementApproved $event)
{
    $procurement = $event->procurement;
    $deptId = $procurement->department_id;

    $budget = Budget::where('department_id', $deptId)
        ->where('year', now()->year)
        ->first();

    DB::transaction(function () use ($procurement, $budget) {
        
        if ($budget && $budget->balance < $procurement->cost_estimate) {
            throw new \Exception('Budget insufficient');
        }

        if ($budget) {
            $budget->spent += $procurement->cost_estimate;
            $budget->balance = $budget->allocated - $budget->spent;
            $budget->save();
        }

        // Invoice create
        Invoice::create([
            'procurement_id' => $procurement->id,
            'invoice_no'     => 'INV-' . strtoupper(Str::random(8)),
            'amount'         => $procurement->cost_estimate,
            'invoice_date'   => now(),
            'status'         => 'unpaid',
        ]);
    });
}

}
