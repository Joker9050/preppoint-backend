<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'interest',
        'message',
        'consent',
        'honeypot',
        'ip_address',
        'status'
    ];

    protected $casts = [
        'consent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'new'
    ];

    /**
     * Get the files associated with this submission
     */
    public function files()
    {
        return $this->hasMany(SubmissionFile::class, 'submission_id');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by interest
     */
    public function scopeByInterest($query, $interest)
    {
        return $query->where('interest', $interest);
    }
}
