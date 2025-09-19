<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Activity Logs
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'payment_ref',
        'invoice_id',
        'payment_date',
        'amount',
        'method',
        'status',
        'transaction_no',
    ];

    // 🔹 Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Payment')
            ->logOnly(['invoice_id', 'payment_date', 'amount', 'method', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Payment record has been {$eventName}";
    }

    // 🔹 Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
