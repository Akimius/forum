<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Thread $thread)
    {

        $thread->addReply(request([
            'body' => request('body'),
            'user_id' => auth()->id,
        ]));

        return back();
    }
}
