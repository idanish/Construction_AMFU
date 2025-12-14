<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasApprovals;

class Budget extends Model implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, SoftDeletes, LogsActivity, HasRoles ;

    protected $fillable = ['title', 'department_id', 'year', 'month', 'allocated', 'spent', 'balance', 'notes','status', 'transaction_no', 'requested_budget', 'budget_type', 'requestor_id', 
    'current_level', 'current_approval_step', 'revert_reason'];

    

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }


    // 🔹 Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Budget')
            ->logOnly(['title', 'department_id', 'year', 'month', 'allocated', 'spent', 'balance', 'notes','status', 'transaction_no', 'requested_budget', 'budget_type', 'requestor_id', 
    'current_level', 'current_approval_step', 'revert_reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Budget record has been {$eventName}";
    }

    // Activity Log End Here

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function requestor() {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'request_id');
    }
}