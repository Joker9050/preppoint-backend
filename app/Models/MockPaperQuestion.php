<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockPaperQuestion extends Model
{
    use HasFactory;

    protected $table = 'mock_paper_questions';

    protected $fillable = [
        'paper_id',
        'section_id',
        'question_id',
        'question_no',
        'marks',
        'negative_marks',
        'order_in_section'
    ];

    protected $casts = [
        'question_no' => 'integer',
        'marks' => 'integer',
        'negative_marks' => 'decimal:2'
    ];

    public function paper()
    {
        return $this->belongsTo(MockExamPaper::class, 'paper_id');
    }

    public function section()
    {
        return $this->belongsTo(MockPaperSection::class, 'section_id');
    }

    public function question()
    {
        return $this->belongsTo(MockQuestion::class, 'question_id');
    }
}
