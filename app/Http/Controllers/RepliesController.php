<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Gate;

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
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(
                request(),
                [
                    'body' =>
                        ['required', new SpamFree()],
                ]
            );
        } catch (\Exception $exception) {
            return response('Sorry, your comment could not be saved at this time', 422);
        }

        $reply->update(['body' => request('body')]);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread)
    {

        if (Gate::denies('create', new Reply)) {
            return response(
                'You are posting too frequently. Please take a break. :)', 429
            );
        }

        try {

            $this->authorize('create', new Reply());

            request()->validate(
                [
                    'body' => ['required', new SpamFree()],
                ]
            );

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
