<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Approval extends Model
{
    use HasFactory, Notifiable, LogsActivity, HasRoles, SoftDeletes;

    protected $fillable = ['request_id', 'approver_id', 'level', 'status', 'comments'];


    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Approval')
            ->logOnly(['request_id', 'approver_id', 'level', 'status', 'comments'])
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

