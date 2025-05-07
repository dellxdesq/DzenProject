<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Likes extends Model
{
    protected $fillable = ['article_id', 'user_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}

