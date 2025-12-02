<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Approval extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['request_id', 'approvable_type', 'approvable_id', 'approver_id', 'status', 'note', 'step_order', 'acted_at', 'approval_step', 'assigned_role', 'revert_reason'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Approval')
            ->logOnly(['request_id', 'approvable_type', 'approvable_id', 'approver_id', 'status', 'note'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Approval record has been {$eventName}";
    }

    // Polymorphic relationship: can belong to RequestModel, Invoice, Procurement, Budget, or Payment
    public function approvable()
    {
        return $this->morphTo();
    }

    // Legacy request relationship for backward compatibility
    public function request()
    {
        return $this->belongsTo(RequestModel::class, 'request_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

