<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procurement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_name',
        'quantity',
        'cost_estimate',
        'department_id',
        'justification',
        'status',
        'attachment',
    ];

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
