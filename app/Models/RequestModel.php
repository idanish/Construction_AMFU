<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    // 👇 Laravel ko batana hoga ke table ka naam "requests" hai
    protected $table = 'requests';

    protected $fillable = [
        'department_id',
        'requestor_id',
        'description',
        'amount',
        'status',
        'comments',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

  public function attachments()
{
    return $this->hasMany(RequestAttachment::class, 'request_id');
}
}
