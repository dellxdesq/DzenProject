<?php

//пока вообще никак не исопльзуется

class ArticleRepository
{
    public function save(array $data): Article
    {
        return Article::create($data);
    }
}

