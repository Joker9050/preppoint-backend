<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockSubject extends Model
{
    use HasFactory;

    protected $table = 'mock_subjects';

    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function topics()
    {
        return $this->hasMany(MockTopic::class, 'subject_id');
    }

    public function questions()
    {
        return $this->hasMany(MockQuestion::class, 'subject_id');
    }
}
