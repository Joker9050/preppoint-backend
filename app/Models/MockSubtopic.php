<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockSubtopic extends Model
{
    use HasFactory;

    protected $table = 'mock_subtopics';

    protected $fillable = [
        'topic_id',
        'name',
        'slug',
        'status'
    ];

    public function topic()
    {
        return $this->belongsTo(MockTopic::class, 'topic_id');
    }

    public function questions()
    {
        return $this->hasMany(MockQuestion::class, 'subtopic_id');
    }
}
