<?php

namespace App\Http\Policies;
use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function update(User $user, Article $article)
    {
        return $user->id === $article->author_id || $user->isModerator();
    }

    public function publish(User $user)
    {
        return $user->isModerator();
    }

    public function delete(User $user)
    {
        return $user->isModerator();
    }
}

