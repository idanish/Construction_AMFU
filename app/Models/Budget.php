<?php

namespace App\Models;
use App\Models\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Budget extends BaseModel

{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
    'department_id',
    'year',
    'allocated',
    'spent',
    'balance',
    'notes',
    'status',
    'attachment',
    'transaction_no',
];

    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('budget')
            ->logOnly(['department_id', 'allocated', 'spent', 'balance', 'status','transaction_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Budget record has been {$eventName}";
    }

    // Activity Log End Here


    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
