<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
        'admin_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function mcqs()
    {
        return $this->hasMany(Mcq::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function subtopics()
    {
        return $this->hasMany(Subtopic::class);
    }
}
