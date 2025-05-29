<?php

namespace App\Http\Services;

use App\Models\Article;
use App\Models\User;

class ArticleModerationService
{
    public function publish(Article $article, User $moderator): void
    {
        $article->update([
            'is_publish' => true,
            'last_editor_id' => $moderator->id,
            'publish_date' => now(),
        ]);
    }

    public function delete(Article $article): void
    {
        $article->update([
            'delete_date' => now(),
        ]);

        $article->delete();
    }
}
