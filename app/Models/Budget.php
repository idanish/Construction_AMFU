<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'department_id', 'attachment', 'year', 'allocated',  'spent', 'balance', 'notes', 'status', 'transaction_no'];

    // 🔹 Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Budget')
            ->logOnly(['title', 'department_id', 'attachment', 'year', 'allocated',  'spent', 'balance', 'notes', 'status', 'transaction_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Budget record has been {$eventName}";
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}