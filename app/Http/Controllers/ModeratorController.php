<?php

namespace App\Http\Controllers;

use App\Http\Services\ArticleModerationService;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;



class ModeratorController extends Controller
{
    public function index()
    {
        $drafts = Article::drafts()->with('author')->get();
        return view('moderator.drafts', compact('drafts'));
    }

    public function publish(Request $request, Article $article, ArticleModerationService $service)
    {
        $this->authorize('publish', $article);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'tags' => 'nullable|string'
        ]);

        $article->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
            'edit_date' => now(),
        ]);

        $service->publish($article, auth()->user());

        return redirect()->route('articles.show', $article)->with('success', 'Статья опубликована');
    }


    public function destroy(Article $article, ArticleModerationService $service)
    {
        $this->authorize('delete', $article);

        $service->delete($article);

        return redirect()->back()->with('success', 'Статья удалена');
    }
}

