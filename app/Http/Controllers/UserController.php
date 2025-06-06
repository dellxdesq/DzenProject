<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Channel;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id());

        if ($request->filled('query') && $request->filled('filter_by')) {
            $column = $request->input('filter_by');
            $search = $request->input('query');

            if (in_array($column, ['login', 'full_name', 'email','id'])) {
                if ($column === 'id') {
                    $query->where($column, $search);
                } else {
                    $query->where($column, 'like', '%' . $search . '%');
                }
            }
        }

        $users = $query->get();

        $moderationArticles = [];
        $user = auth()->user();
        if ($user->hasRole('moder') || $user->hasRole('admin')) {
            $moderationArticles = Article::where('is_publish', false)
                ->whereNull('delete_date')
                ->orderBy('created_at', 'desc')
                ->with('author')
                ->get();
        }

        return view('users.index', compact('users', 'moderationArticles'));
    }

    public function makeAuthor(Request $request, User $user)
    {
        $authorRole = Role::where('name', 'author')->first();
        if (!$authorRole) {
            return back()->with('error', 'Роль "author" не найдена');
        }

        if ($request->input('author') == '1') {
            $user->roles()->syncWithoutDetaching([$authorRole->id]);

            if (!$user->channel) {
                Channel::create([
                    'user_id' => $user->id,
                    'name' => 'Канал ' . $user->full_name,
                    'description' => 'Описание канала по умолчанию',
                    'photo' => null,
                ]);
            }

        } else {
            $user->roles()->detach($authorRole->id);

            if ($user->channel) {
                $user->channel->articles()->delete();
                $user->channel->delete();
            }
        }

        return back();
    }

    public function makeModerator(Request $request, User $user)
    {
        $moderRole = Role::where('name', 'moder')->first();
        $authorRole = Role::where('name', 'author')->first();
        if (!$moderRole) {
            return back()->with('error', 'Роль "moder" не найдена');
        }

        if ($request->input('moder') == '1') {
            $user->roles()->sync([$moderRole->id]);
        } else {
            $user->roles()->detach($moderRole->id);
        }

        return back();
    }
    public function ban(Request $request, User $user)
    {
        $banRole = Role::where('name', 'ban')->first();
        if (!$banRole) {
            return back()->with('error', 'Роль "ban" не найдена');
        }

        if ($request->input('ban') == '1') {
            $user->roles()->syncWithoutDetaching([$banRole->id]);
        } else {
            $user->roles()->detach($banRole->id);
        }

        $user->save();

        return back();
    }


    public function edit()
    {
        $user = auth()->user();

        $moderationArticles = [];

        if ($user->hasRole('moder') || $user->hasRole('admin')) {
            $moderationArticles = Article::where('is_publish', false)
                ->whereNull('delete_date')
                ->orderBy('created_at', 'desc')
                ->with('author')
                ->get();
        }

        return view('profile.edit', compact('moderationArticles', 'user'));
    }
    public function show()
    {
        $user = auth()->user();

        $moderationArticles = [];

        if ($user->hasRole('moder') || $user->hasRole('admin')) {
            $moderationArticles = Article::where('is_publish', false)
                ->whereNull('delete_date')
                ->orderBy('created_at', 'desc')
                ->with('author')
                ->get();
        }

        return view('profile.show', compact('moderationArticles'));
    }


}
