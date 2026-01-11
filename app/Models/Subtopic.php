<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'name',
        'admin_id',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function mcqs()
    {
        return $this->hasMany(Mcq::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
