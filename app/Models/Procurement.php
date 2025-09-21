<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Procurement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['item_name', 'quantity', 'cost_estimate', 'department_id', 'remarks', 'status', 'attachment'];

    // 🔹 Activity Log
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

    // 🔹 Relationships
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