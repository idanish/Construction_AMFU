<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ServiceRequest extends Model
{
    use HasFactory, LogsActivity;

    // ServiceRequest.php model
 protected $fillable = ['request_no', 'description', 'status', 'transaction_no'];



    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ServiceRequest')
            ->logOnly(['request_no', 'description', 'status', 'transaction_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Service record has been {$eventName}";
    }

    // Activity Log End Here




    // ek request ke multiple invoices ho sakte hain
  // Agar har service request ka sirf ek invoice hai
public function invoice()
{
    return $this->hasOne(Invoice::class, 'request_id'); // 'request_id' Invoice table me foreign key
}

// Agar multiple invoices ho sakti hain
public function invoices()
{
    return $this->hasMany(Invoice::class, 'request_id');
}


}
