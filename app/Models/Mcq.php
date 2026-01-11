<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'question_no',
        'question',
        'question_code',
        'question_image',
        'options',
        'correct_option',
        'explanation',
        'difficulty',
        'status',
        'subtopic_id',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }
}
