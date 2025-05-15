<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Likes;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Services\ArticleService;
use Illuminate\Http\Request;


class ArticleController extends Controller
{
    public function create()
    {
        return view('articles.create');
    }

    public function store(StoreArticleRequest $request, ArticleService $service)
    {
        $article = $service->create($request->validated(), $request->file('preview'));

        return redirect()
            ->route('articles.show', $article->id)
            ->with('success', 'Статья создана!');
    }

    public function like($id)
    {
        $userId = auth()->id();

        $existingLike = Likes::where('article_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Likes::create([
                'article_id' => $id,
                'user_id' => $userId,
            ]);
        }

        return back();
    }


    public function comment(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|max:1000'
        ]);

        Comment::create([
            'article_id' => $id,
            'user_id' => auth()->id(),
            'text' => $request->input('text')
        ]);

        return back();
    }

    public function show($id)
    {
        $article = Article::with(['likes.user', 'comments.user'])->findOrFail($id);
        return view('articles.show', compact('article'));
    }
}

