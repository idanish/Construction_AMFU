<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
// Activity Logs
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Procurement extends Model
{
    use HasFactory, SoftDeletes,  LogsActivity, HasRoles ;

    protected $fillable = ['item_name', 'quantity', 'cost_estimate', 'department_id', 'remarks', 'status', 'attachment'];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Procurement')
            ->logOnly(['item_name', 'quantity', 'cost_estimate', 'department_id', 'remarks', 'status', 'attachment'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Procurement record has been {$eventName}";
    }

    // Activity Log End Here

    //  Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approvals()
    {
        return $this->hasMany(ProcurementApproval::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'procurement_id');
    }

    public function procurement()
    {
    return $this->belongsTo(\App\Models\Procurement::class, 'procurement_id');
    }

}