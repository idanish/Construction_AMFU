<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends BaseModel
{
    use HasFactory;

    protected $fillable = ['department_id', 'allocated', 'spent', 'balance', 'status','transaction_no'];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
