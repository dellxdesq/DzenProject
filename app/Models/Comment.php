<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    protected $fillable = ['article_id', 'user_id', 'text'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}

