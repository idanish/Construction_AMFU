<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
// Activity Logs
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasApprovals;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasRoles, HasApprovals;

    protected $fillable = ['procurement_id', 'invoice_no', 'amount', 'invoice_date', 'vendor_name', 'due_date', 'status', 'current_approval_step', 'revert_reason', 'approved_at', 'notes', 'attachment'];

    protected $casts = [
        'invoice_date' => 'date',
        'approved_at' => 'datetime',
        'attachment' => 'array',
    ];

    //  Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Invoice')
            ->logOnly(['procurement_id', 'invoice_no', 'amount', 'invoice_date', 'vendor_name', 'due_date', 'status', 'notes', 'attachment'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Invoice record has been {$eventName}";
    }

    // Activity Log End Here

    //  Relationships
    public function procurement()
    {
        return $this->belongsTo(Procurement::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
