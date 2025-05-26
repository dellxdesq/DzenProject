<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FilterController extends Controller{
    public function index(Request $request)
    {
        $categoryId = $request->input('category');

        $articles = Article::with(['author', 'categories'])
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            })
            ->paginate(9);

        $categories = Category::where('is_hidden', false)->get();

        return view('dashboard', compact('articles', 'categories'));
    }
}

