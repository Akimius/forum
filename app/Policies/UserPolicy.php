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
     * Determine whether the user can update the given profile.
     *
     * @param User $signedInUser
     * @param User $user
     * @return boolean
     */
    public function update(User $signedInUser, User $user): bool
    {
        return $signedInUser->id === $user->id;
    }
}
