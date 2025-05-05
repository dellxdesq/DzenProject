<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function create()
    {
        return view('article.create');
    }

    public function store(StoreArticleRequest $request, ArticleService $service)
    {
        $service->create($request->validated(), $request->file('preview'));
        return redirect()->route('home')->with('success', 'Статья создана!');
    }
}

