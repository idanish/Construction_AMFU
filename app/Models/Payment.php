<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
// Activity Logs
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasRoles ;

    protected $fillable = ['payment_ref', 'invoice_id', 'payment_date', 'amount', 'method', 'attachment', 'transaction_no'];

    //  Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Payment')
            ->logOnly(['payment_ref', 'invoice_id', 'payment_date', 'amount', 'method', 'attachment', 'transaction_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Payment record has been {$eventName}";
    }

     // Activity Log End Here
     
    //  Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
}
