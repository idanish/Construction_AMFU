<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class RequestModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'requests';

    protected $fillable = ['requestor_id', 'department_id', 'description', 'amount', 'comments', 'status'];



    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('RequestModel')
            ->logOnly(['requestor_id', 'department_id', 'description', 'amount', 'comments', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Request has been {$eventName}";
    }

    // Activity Log End Here




    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approvals()
{
    return $this->hasMany(Approval::class, 'request_id');
}

}
