<?php
namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;

class ArticleRepository
{
    public function getAll(array $filters = []): Builder
    {
        $query = Article::with(['author', 'tags', 'categories'])
            ->where('is_publish', true)
            ->orderByDesc('publish_date');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category']);
            });
        }

        return $query;
    }
}

