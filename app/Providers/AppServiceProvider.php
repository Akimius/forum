<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 1st option
        \View::composer('*', function ($view) {

            $channels = Cache::rememberForever('channels', function () {

                return Channel::all();

            });

            $view->with('channels', $channels);
        });

        // 2nd option
        //\View::share('channels', Channel::all());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
