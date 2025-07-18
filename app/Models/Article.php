<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'source', 'category', 'author', 'description', 'url', 'published_at'
    ];
}
