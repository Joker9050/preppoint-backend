<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'topic_ids',
        'mcq_ids',
        'time_limit',
        'total_questions',
        'is_ssc_pyq',
        'is_active',
        'admin_id',
        'instructions',
    ];

    protected $casts = [
        'topic_ids' => 'array',
        'mcq_ids' => 'array',
        'is_ssc_pyq' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function topics()
    {
        return Topic::whereIn('id', $this->topic_ids ?? [])->get();
    }

    public function mcqs()
    {
        return Mcq::whereIn('id', $this->mcq_ids ?? [])->get();
    }
}
