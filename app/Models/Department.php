<?php

namespace App\Models;
use App\Models\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description','transaction_no'];
}
