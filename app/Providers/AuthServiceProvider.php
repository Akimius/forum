<?php

namespace App\Providers;

use App\Policies\ReplyPolicy;
use App\Policies\ThreadPolicy;
use App\Policies\UserPolicy;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class => ReplyPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(
            static function ($user) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
