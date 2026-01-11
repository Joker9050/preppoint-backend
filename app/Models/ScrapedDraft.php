<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ScrapedDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'source_url',
        'source_name',
        'scraped_data',
        'status',
        'approved_by',
        'rejected_by',
        'published_at',
        'admin_id',
        'change_log',
    ];

    protected $casts = [
        'scraped_data' => 'array',
        'change_log' => 'array',
        'published_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(Admin::class, 'rejected_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>',
            'approved' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>',
            'rejected' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>',
            'published' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Published</span>',
            default => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function addChangeLog($action, $adminId, $notes = null)
    {
        $log = [
            'action' => $action,
            'admin_id' => $adminId,
            'admin_name' => Admin::find($adminId)->name ?? 'Unknown',
            'timestamp' => now()->toISOString(),
            'notes' => $notes,
        ];

        $currentLog = $this->change_log ?? [];
        $currentLog[] = $log;
        $this->change_log = $currentLog;
        $this->save();
    }
}
