<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    // Agar fillable chahiye
    protected $fillable = [
        'request_id',
        'invoice_no',
        'amount',
        'status',
        'invoice_date',
        'notes',
        'attachment',
        'transaction_no'
    ];

    protected $casts = [
    'invoice_date' => 'date',
];


    // Relationship define karo
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
