<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestRepliesController extends Controller
{
    public function store(Reply $reply): void
    {
        //abort_if($reply->thread->user_id !== auth()->id(), 403);

        // Or
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
    }
}
