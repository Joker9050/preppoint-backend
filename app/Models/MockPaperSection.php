<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockPaperSection extends Model
{
    use HasFactory;

    protected $table = 'mock_paper_sections';

    protected $fillable = [
        'paper_id',
        'name',
        'total_questions',
        'section_marks',
        'section_time_minutes',
        'sequence_no',
        'positive_marks',
        'negative_marks',
        'instructions'
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'section_marks' => 'integer',
        'section_time_minutes' => 'integer',
        'sequence_no' => 'integer',
        'positive_marks' => 'decimal:2',
        'negative_marks' => 'decimal:2'
    ];

    public function paper()
    {
        return $this->belongsTo(MockExamPaper::class, 'paper_id');
    }

    public function questions()
    {
        return $this->hasMany(MockPaperQuestion::class, 'section_id');
    }
}
