<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function show($id)
    {
        $channel = Channel::with('articles')->findOrFail($id);
        return view('channel.show', compact('channel'));
    }
}
