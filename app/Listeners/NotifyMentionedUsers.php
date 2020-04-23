<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReplyEvent;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
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
     * @param ThreadReceivedNewReplyEvent $event
     * @return void
     */
    public function handle(ThreadReceivedNewReplyEvent $event): void
    {
        collect($event->reply->mentionedUsers())
            ->map(
                static function ($name) {
                    return User::whereName($name)->first();
                }
            )
            ->filter() // to filter out null values if no such users
            ->each(
                static function ($user) use ($event) {
                    $user->notify(new YouWereMentioned($event->reply));
                }
            );
    }
}
