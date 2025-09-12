<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class RequestModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'requests';

    protected $fillable = [
        'requestor_id',
        'department_id',
        'description',
        'amount',
        'comments',
        'status',
    ];

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approvals()
{
    return $this->hasMany(Approval::class, 'request_id');
}

}
