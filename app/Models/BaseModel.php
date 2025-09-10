<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\BaseObserver;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::observe(BaseObserver::class);
    }
}
