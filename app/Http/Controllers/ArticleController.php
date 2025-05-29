<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Likes;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Repositories\ArticleRepository;
use League\CommonMark\CommonMarkConverter;

class ArticleController extends Controller
{
    public function dashboard(Request $request, ArticleRepository $repository)
    {
        $filters = $request->only(['search', 'category']);
        $articles = $repository->getAll($filters)->paginate(12);
        $categories = Category::all();

        return view('dashboard', compact('articles', 'categories'));
    }

    public function preview($filename)
    {
        $path = base_path('storage/articles_photo/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function index(Request $request, ArticleRepository $repository)
    {
        $filters = $request->only(['search', 'category']);
        $articles = $repository->getAll($filters)->paginate(12);
        $categories = Category::all();

        return view('articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        if (!auth()->user()?->hasRole('author')) {
            abort(403, 'Недостаточно прав для создания статьи.');
        }

        return view('articles.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()?->hasRole('author')) {
            abort(403, 'Недостаточно прав для создания статьи.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'preview' => 'nullable|image',
            'tags' => 'nullable|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $previewPath = null;

        if ($request->hasFile('preview')) {
            $file = $request->file('preview');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = base_path('storage/articles_photo');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $previewPath = 'storage/articles_photo/' . $filename;
        }

        $article = Article::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'preview_path' => $previewPath,
            'created_date' => Carbon::now(),
            'author_id' => auth()->id(),
            'is_publish' => false,
            'channel_id' => $request->input('channel_id'),
        ]);

        $article->categories()->sync($request->input('categories'));

        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            foreach ($tags as $tag) {
                Tag::create([
                    'article_id' => $article->id,
                    'name' => trim($tag),
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Черновик статьи создан и отправлен на модерацию.');
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
            'text' => 'required|string|max:1000',
        ]);

        Comment::create([
            'article_id' => $id,
            'user_id' => auth()->id(),
            'text' => $request->input('text'),
        ]);

        return back();
    }

    public function show($id)
    {
        $article = Article::with(['channel', 'author', 'likes.user', 'comments.user', 'tags'])->findOrFail($id);

        $content = $article->content;

        $contentWithBr = preg_replace("/\n\s*\n/", "\n<br>\n", $content);

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'renderer' => [
                'soft_break' => '<br>',
            ],
        ]);

        $article->content_html = $converter->convert($contentWithBr)->getContent();

        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        if (auth()->id() !== $article->author_id && !auth()->user()->hasRole('moder')) {
            abort(403);
        }

        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if (auth()->id() !== $article->author_id && !auth()->user()->hasRole('moder')) {
            abort(403);
        }

        $data = $request->validate([
            'description' => 'nullable|string',
            'content' => 'nullable|string'
        ]);

        $article->description = $data['description'] ?? null;
        $article->content = $data['content'] ?? null;
        $article->edit_date = now();

        if ($request->has('publish') && auth()->user()->hasRole('moder')) {
            $article->is_publish = true;
            $article->publish_date = now();
            $article->last_editor_id = auth()->id();
        }

        $article->save();

        return redirect()->route('articles.show', $article)->with('success', 'Статья обновлена');
    }


    public function publish(Article $article)
    {
        if (!auth()->user()->hasRole('moder')) {
            abort(403);
        }

        $article->update([
            'is_publish' => true,
            'publish_date' => now()
        ]);

        return redirect()->route('articles.show', $article)->with('success', 'Статья опубликована');
    }

    public function destroy(Article $article)
    {
        if (!auth()->user()->hasRole('moder')) {
            abort(403, 'Нет прав для удаления статьи');
        }

        if ($article->preview_path && file_exists(base_path($article->preview_path))) {
            unlink(base_path($article->preview_path));
        }

        $article->tags()->delete();
        $article->likes()->delete();
        $article->comments()->delete();

        $article->delete();

        return redirect()->route('dashboard')->with('success', 'Статья удалена');
    }



}
