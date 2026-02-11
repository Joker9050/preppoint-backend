<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'subcategory_id',
        'name',
        'admin_id',
        'priority',
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
