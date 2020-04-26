<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param User $signedInUser
     * @return bool
     */
    public function update(User $user, User $signedInUser): bool
    {
        return $signedInUser->id === $user->id;
    }
}
