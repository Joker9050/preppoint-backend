<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'mock_question_options';

    protected $fillable = [
        'question_id',
        'option_label',
        'option_text',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function question()
    {
        return $this->belongsTo(MockQuestion::class, 'question_id');
    }
}
