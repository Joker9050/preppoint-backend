<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMockAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'exam_id',
        'paper_id',
        'status',
        'total_questions',
        'attempted_questions',
        'correct_answers',
        'score',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    /**
     * Get the user that owns the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exam associated with the attempt.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(MockExam::class, 'exam_id');
    }

    /**
     * Get the paper associated with the attempt.
     */
    public function paper(): BelongsTo
    {
        return $this->belongsTo(MockExamPaper::class, 'paper_id');
    }
}
