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

        $articlesQuery = $channel->articles()->with(['likes', 'comments']);

        if (auth()->id() === $channel->user_id) {
            $articles = $articlesQuery->get();
        } else {
            $articles = $articlesQuery->whereNotNull('publish_date')->get();
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
