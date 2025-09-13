<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    // Agar tum BaseModel se inherit kar rahe ho aur usme transaction_no save ho raha hai,
    // to usko hata dena. Yahan hum directly Model extend karte hain.
    
    protected $fillable = [
        'title',
        'department_id',
        'allocated',
        'spent',
        'balance',
        'status',
        'attachment',
        'transaction_no',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
