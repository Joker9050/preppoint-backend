<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'admin_id',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
