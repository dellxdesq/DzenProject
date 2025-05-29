<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'article_id',
    ];

    public function post()
    {
        return $this->belongsTo(Article::class);
    }
}


