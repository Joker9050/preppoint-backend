<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockQuestion extends Model
{
    use HasFactory;

    protected $table = 'mock_questions';

    protected $fillable = [
        'subject_id',
        'topic_id',
        'subtopic_id',
        'question_text',
        'question_type',
        'difficulty_level',
        'explanation'
    ];

    public function subject()
    {
        return $this->belongsTo(MockSubject::class, 'subject_id');
    }

    public function topic()
    {
        return $this->belongsTo(MockTopic::class, 'topic_id');
    }

    public function subtopic()
    {
        return $this->belongsTo(MockSubtopic::class, 'subtopic_id');
    }

    public function options()
    {
        return $this->hasMany(MockQuestionOption::class, 'question_id');
    }

    public function correctOption()
    {
        return $this->hasOne(MockQuestionOption::class, 'question_id')->where('is_correct', true);
    }

    public function paperQuestions()
    {
        return $this->hasMany(MockPaperQuestion::class, 'question_id');
    }
}
