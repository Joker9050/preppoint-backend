<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockExam extends Model
{
    use HasFactory;

    protected $table = 'mock_exams';

    protected $fillable = [
        'name',
        'short_name',
        'category',
        'mode',
        'status',
        'ui_template',
        'description',
        'slug'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function papers()
    {
        return $this->hasMany(MockExamPaper::class, 'exam_id');
    }

    public function livePapers()
    {
        return $this->hasMany(MockExamPaper::class, 'exam_id')->where('is_live', true);
    }
}
