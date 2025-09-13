<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Procurement extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['title', 'department_id', 'description', 'attachment', 'status'];


    
    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Procurement')
            ->logOnly(['title', 'department_id', 'description', 'attachment', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Procurement record has been {$eventName}";
    }

    // Activity Log End Here




    public function department() {
        return $this->belongsTo(Department::class);
    }
}
