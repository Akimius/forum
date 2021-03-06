<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param Reply $reply
     *
     * @return bool
     */
    public function update(User $user, Reply $reply): bool
    {
        return (int)$reply->user_id === (int)$user->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        $lastReply = $user->fresh()->lastReply;

        if (! $lastReply) {
            return true;
        }

        return ! $lastReply->wasJustPublished();
    }
}
