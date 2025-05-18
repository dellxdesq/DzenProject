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
        $channel = Channel::with('articles')->findOrFail($id);
        return view('channel.show', compact('channel'));
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
        $channel->delete();

        return redirect()->route('dashboard')->with('success', 'Канал удалён');
    }

}
