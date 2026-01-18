<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime'
    ];

    /**
     * Get the submission that owns this file
     */
    public function submission()
    {
        return $this->belongsTo(ContactSubmission::class, 'submission_id');
    }

    /**
     * Get the full URL for the file
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/contact-files/' . $this->file_path);
    }

    /**
     * Get human readable file size
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
