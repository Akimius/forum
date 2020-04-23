<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReplyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyThreadSubscribers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReplyEvent  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReplyEvent $event)
    {
        $event->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
