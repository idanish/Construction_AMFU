<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['request_id', 'invoice_no', 'amount', 'status'];

    public function request() {
        return $this->belongsTo(Request::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}

