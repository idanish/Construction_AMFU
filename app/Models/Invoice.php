<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Activity Logs
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'procurement_id',
        'invoice_no',
        'amount',
        'invoice_date',
        'status',
        'notes',
        'transaction_no',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    // 🔹 Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Invoice')
            ->logOnly(['procurement_id', 'invoice_no', 'amount', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Invoice record has been {$eventName}";
    }

    // 🔹 Relationships
    public function procurement()
    {
        return $this->belongsTo(Procurement::class, 'procurement_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
