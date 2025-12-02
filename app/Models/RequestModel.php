<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Yeh line add karein
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class RequestModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity, SoftDeletes, HasRoles ;

    protected $table = 'requests';

    protected $fillable = ['requestor_id', 'department_id', 'title', 'description', 'amount', 'comments', 'status', 'current_approval_step', 'revert_reason', 'approved_at'];

    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('RequestModel')
            ->logOnly(['requestor_id', 'department_id', 'title', 'description', 'amount', 'comments', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Request has been {$eventName}";
    }

    // Activity Log End Here


//  Relationship with User
    public function user()
    {
        // return $this->belongsTo(User::class, 'user_id');
        return $this->belongsTo(User::class, 'requestor_id');
    }

    //  Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function requestor() {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function approvals()
    {
        return $this->hasMany(\App\Models\Approval::class, 'request_id');
    }

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'approvals', 'request_id', 'approver_id')->withPivot('status', 'note', 'acted_at', 'approval_step', 'revert_reason');
    }

    // Get the current pending approval for this request
    public function currentApproval()
    {
        return $this->approvals()->where('status', 'pending')->orderBy('step_order')->first();
    }

    // Get all approved steps
    public function approvedSteps()
    {
        return $this->approvals()->where('status', 'approved')->get();
    }

    // Get all rejected steps
    public function rejectedSteps()
    {
        return $this->approvals()->where('status', 'rejected')->get();
    }

    // Check if request is fully approved
    public function isFullyApproved()
    {
        $totalSteps = $this->approvals()->count();
        $approvedCount = $this->approvals()->where('status', 'approved')->count();
        return $totalSteps > 0 && $totalSteps === $approvedCount;
    }

}
