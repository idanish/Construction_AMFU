<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Approval extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['request_id', 'approver_id', 'status', 'comments'];


    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Approval')
            ->logOnly(['request_id', 'approver_id', 'status', 'comments'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Approval record has been {$eventName}";
    }

    // Activity Log End Here





    public function request()
    {
        return $this->belongsTo(RequestModel::class, 'request_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    
}
