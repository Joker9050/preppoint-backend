<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'organization',
        'category',
        'description',
        'important_dates',
        'apply_url',
        'admit_card_url',
        'result_url',
        'status',
        'published_at',
        'admin_id',
    ];

    protected $casts = [
        'important_dates' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobUpdate) {
            if (empty($jobUpdate->slug)) {
                $jobUpdate->slug = Str::slug($jobUpdate->title);
            }
        });

        static::updating(function ($jobUpdate) {
            if ($jobUpdate->isDirty('title') && empty($jobUpdate->slug)) {
                $jobUpdate->slug = Str::slug($jobUpdate->title);
            }
        });
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'published' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Published</span>',
            'draft' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>',
            'archived' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Archived</span>',
            default => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getCategoryBadgeAttribute()
    {
        return match($this->category) {
            'govt' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Government</span>',
            'private' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Private</span>',
            'bank' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Banking</span>',
            'it' => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">IT</span>',
            default => '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Other</span>',
        };
    }
}
