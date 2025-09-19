<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activitylog extends Model
{
    protected $fillable = ['user_id',
    'action',
    'metadata',
    ];
}
