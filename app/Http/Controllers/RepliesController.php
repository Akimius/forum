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
     * @throws \Exception
     */
    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(),[
                'body' => 'required'
            ]);

            $spam->detect(request('body'));

        } catch (\Exception $exception) {
            return response('Sorry, your comment could not be saved at this time', 422);
        }

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
        try {
            $this->validate(request(), ['body' => 'required']);

            $spam->detect(request('body'));

            $reply = $thread->addReply(
                [
                    'body' => request('body'),
                    'user_id' => auth()->id(),
                ]
            );
        } catch (\Exception $exception) {
            return response('Sorry, your comment could not be saved at this time', 422);
        }


//        if (request()->expectsJson()) {
//            return $reply->load('owner');
//        }
//        return back()
//            ->with('flash', auth()->user()->name . ' has left a reply');

        // or just:
        return $reply->load('owner');
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
