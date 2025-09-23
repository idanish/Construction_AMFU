<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Yeh line add karein
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class RequestModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $table = 'requests';

    protected $fillable = ['requestor_id', 'department_id', 'title', 'description', 'amount', 'comments', 'status'];

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


// 🔹 Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔹 Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function requestor() {
        return $this->belongsTo(User::class, 'requestor_id');
    }
    




}
