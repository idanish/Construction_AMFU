<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    // 🔗 Attachment belongs to a Request
    public function request()
{
    return $this->belongsTo(RequestModel::class, 'request_id');
}
}
