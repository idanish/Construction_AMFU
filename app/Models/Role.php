<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Activity Logs Files
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Role extends Model
{
    use LogsActivity;
    protected $fillable = ['name'];

    // Activity Log Start Here

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Role')
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Role has been {$eventName}";
    }

    // Activity Log End Here

    
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
