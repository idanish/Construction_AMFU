<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Payment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['invoice_id', 'payment_date', 'amount', 'status'];


    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Payment')
            ->logOnly(['invoice_id', 'payment_date', 'amount', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Payment record has been {$eventName}";
    }

    // Activity Log End Here



    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function payments()
{
    return $this->hasMany(Payment::class);
}

}
