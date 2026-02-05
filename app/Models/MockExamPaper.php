<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockExamPaper extends Model
{
    use HasFactory;

    protected $table = 'mock_exam_papers';

    protected $fillable = [
        'exam_id',
        'title',
        'paper_type',
        'year',
        'shift',
        'total_questions',
        'total_marks',
        'duration_minutes',
        'difficulty_level',
        'is_live',
        'instructions',
        'ui_template',
        'status'
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'total_marks' => 'integer',
        'duration_minutes' => 'integer',
        'year' => 'integer',
        'is_live' => 'boolean'
    ];

    public function exam()
    {
        return $this->belongsTo(MockExam::class, 'exam_id');
    }

    public function sections()
    {
        return $this->hasMany(MockPaperSection::class, 'paper_id');
    }

    public function questions()
    {
        return $this->hasManyThrough(
            MockQuestion::class,
            MockPaperQuestion::class,
            'paper_id',
            'id',
            'id',
            'question_id'
        );
    }

    public function paperQuestions()
    {
        return $this->hasMany(MockPaperQuestion::class, 'paper_id');
    }
}
