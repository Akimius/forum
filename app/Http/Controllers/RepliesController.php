<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(4);
    }

    /**
     * @param Reply $reply
     * @param Spam $spam
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function update(Reply $reply, Spam $spam): void
    {
        $this->authorize('update', $reply);

        $this->validate(request(),[
            'body' => 'required'
            ]);

        $spam->detect(request('body'));

        $reply->update(['body' => request('body')]);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @param Spam $spam
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store($channelId, Thread $thread, Spam $spam)
    {
        $this->validate(request(), ['body' => 'required']);

        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()
            ->with('flash', auth()->user()->name . ' has left a reply');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) { // redirect only in case of Ajax request
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
