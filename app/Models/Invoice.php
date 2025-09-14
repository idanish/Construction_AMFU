<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    // Agar fillable chahiye
    protected $fillable = ['request_id', 'invoice_no', 'amount', 'status'];

    
    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Invoice')
            ->logOnly(['request_id', 'invoice_no', 'amount', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Invoice record has been {$eventName}";
    }

    // Activity Log End Here


    // Relationship define karo
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
