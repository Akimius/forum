<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        return back()
            ->with('flash', auth()->user()->name . " has left a reply");
    }

    public function destroy(Reply $reply)
    {
        if ($reply->user_id != auth()->id()) {
            return response([], 403);
        }

        $reply->delete();

        return back();
    }
}
