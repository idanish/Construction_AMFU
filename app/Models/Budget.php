<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasApprovals;



class Budget extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasRoles, HasApprovals;

    protected $fillable = ['title', 'department_id', 'attachment', 'year', 'allocated',  'requested_budget', 'budget_type', 'spent', 'balance', 'notes', 'status', 'current_approval_step', 'revert_reason', 'approved_at', 'transaction_no'];

    protected $casts = [
        'approved_at' => 'datetime',
        'attachment' => 'array',
    ];

    // 🔹 Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Budget')
            ->logOnly(['title', 'department_id', 'attachment', 'year', 'allocated',  'requested_budget', 'budget_type', 'spent', 'balance', 'notes', 'status', 'transaction_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Budget record has been {$eventName}";
    }

    // Activity Log End Here

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}