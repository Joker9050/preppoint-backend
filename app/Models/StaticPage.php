<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = [
        'page_name',
        'title',
        'content',
        'admin_id'
    ];

    protected $casts = [
        'admin_id' => 'integer',
    ];

    /**
     * Find page by page_name
     */
    public static function findByPageName($pageName)
    {
        return static::where('page_name', $pageName)->first();
    }
}
