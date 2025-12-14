<?php

namespace App\Traits;

use App\Models\Approval;

trait HasApprovals
{
    /**
     * Get all approvals for this model (polymorphic relationship).
     */
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * Get the current approval step for this model.
     */
    public function currentApproval()
    {
        return $this->approvals()
            ->where('status', 'pending')
            ->orderBy('step_order', 'asc')
            ->first();
    }

    /**
     * Get all approved steps for this model.
     */
    public function approvedSteps()
    {
        return $this->approvals()
            ->where('status', 'approved')
            ->orderBy('step_order', 'asc')
            ->get();
    }

    /**
     * Get all rejected steps for this model.
     */
    public function rejectedSteps()
    {
        return $this->approvals()
            ->where('status', 'rejected')
            ->get();
    }

    /**
     * Check if this model is fully approved (all steps approved).
     */
    public function isFullyApproved()
    {
        $totalSteps = $this->approvals()->count();
        $approvedSteps = $this->approvals()->where('status', 'approved')->count();
        return $totalSteps > 0 && $totalSteps === $approvedSteps;
    }

    /**
     * Check if this model has been reverted (rejected at any step).
     */
    public function isReverted()
    {
        return $this->approvals()->where('status', 'rejected')->exists();
    }
}
