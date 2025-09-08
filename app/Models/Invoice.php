<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Agar fillable chahiye
    protected $fillable = ['request_id', 'invoice_no', 'amount', 'status'];

    // Relationship define karo
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
