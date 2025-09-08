<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    // ServiceRequest.php model
 protected $fillable = [
        'request_no',
        
        'description',
        'status',
    ];


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
