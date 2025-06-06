<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'description', 'preview_path', 'author_id', 'created_date', 'publish_date', 'is_publish', 'channel_id'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }


    public function tags()
    {
        return $this->hasMany(Tag::class, 'article_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class);
    }

    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_editor_id');
    }

    public function scopeDrafts($query)
    {
        return $query->where('is_publish', false);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}

