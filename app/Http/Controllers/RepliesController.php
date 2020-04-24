<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
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
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException|\Illuminate\Validation\ValidationException
     */
    public function update(Reply $reply): void
    {
        $this->authorize('update', $reply);

            $this->validate(
                request(),
                [
                    'body' =>
                        ['required', new SpamFree()],
                ]
            );

        $reply->update(['body' => request('body')]);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @param CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        //return $form->persist($thread);
        return $thread->addReply(
            [
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]
        )->load('owner');
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
