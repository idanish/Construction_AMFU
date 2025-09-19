<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementApproval extends Model
{
   
    // Fillable fields for mass assignment
    protected $fillable = [
        'procurement_id',
        'approved_by',   // User ID who approves/rejects
        'role',          // Role of approver (hod, finance etc.)
        'status',        // pending, approved, rejected
        'remarks',       // optional comments
    ];

    /**
     * 🔹 Relationship with Procurement
     * One approval belongs to one procurement
     */
    public function procurement()
    {
        return $this->belongsTo(Procurement::class);
    }

    /**
     * 🔹 Relationship with User (approver)
     * approved_by references users table
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
