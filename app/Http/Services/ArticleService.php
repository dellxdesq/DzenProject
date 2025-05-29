<?php

namespace App\Http\Services;

use App\Models\Article;
use Illuminate\Http\UploadedFile;

class ArticleService
{
    public function create(array $data, ?UploadedFile $preview): Article
    {
        if ($preview) {
            $path = $preview->store('previews', 'public');
            $data['preview_path'] = $path;
        }

        //$data['author_id'] = 1; //тут фиксить

        return Article::create($data);
    }
}

