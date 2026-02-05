<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTopic extends Model
{
    use HasFactory;

    protected $table = 'mock_topics';

    protected $fillable = [
        'subject_id',
        'name',
        'slug'
    ];

    public function subject()
    {
        return $this->belongsTo(MockSubject::class, 'subject_id');
    }

    public function subtopics()
    {
        return $this->hasMany(MockSubtopic::class, 'topic_id');
    }

    public function questions()
    {
        return $this->hasMany(MockQuestion::class, 'topic_id');
    }
}
