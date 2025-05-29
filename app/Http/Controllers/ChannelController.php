<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChannelController extends Controller
{
    use AuthorizesRequests;
    public function show($id)
    {
        $channel = Channel::findOrFail($id);
        $user = auth()->user();

        $articlesQuery = $channel->articles()->with(['likes', 'comments']);

        $isOwnerOrModer = $user && (
                $user->id === $channel->user_id ||
                $user->hasRole('moder') ||
                $user->hasRole('admin')
            );

        if ($isOwnerOrModer) {
            $drafts = (clone $articlesQuery)
                ->where('is_publish', false)
                ->orderBy('created_at', 'desc')
                ->get();

            $published = (clone $articlesQuery)
                ->where('is_publish', true)
                ->orderBy('publish_date', 'desc')
                ->get();

            $articles = $drafts->concat($published);
        } else {
            $articles = $articlesQuery
                ->where('is_publish', true)
                ->orderBy('publish_date', 'desc')
                ->get();
        }

        return view('channel.show', compact('channel', 'articles'));
    }

    public function update(Request $request, Channel $channel)
    {
        $this->authorize('update', $channel);
        $channel->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('channel_photos', 'public');
            $channel->photo = 'storage/' . $path;
            $channel->save();
        }

        return back()->with('success', 'Канал обновлён');
    }

    public function destroy(Channel $channel)
    {
        $this->authorize('delete', $channel);
        $channel->articles()->delete();
        $channel->delete();

        return redirect()->route('dashboard')->with('success', 'Канал удалён');
    }

}
