<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'allocated', 'spent', 'balance', 'status'];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
