<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\Department;
use App\Models\ApprovalLevel;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
        'approval_level_id',
        'transaction_no'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Multiple Departments Relationship
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function approvalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class, 'approval_level_id');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('User')
            ->logOnly(['name', 'username', 'email', 'status', 'approval_level_id', 'transaction_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User record has been {$eventName}";
    }
}
