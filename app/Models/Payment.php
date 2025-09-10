<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['invoice_id', 'payment_date', 'amount', 'status'];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function payments()
{
    return $this->hasMany(Payment::class);
}

}
