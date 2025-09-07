<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'department_id', 'description', 'attachment', 'status'];

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
