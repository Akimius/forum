<?php

use App\Thread;
use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Thread::class, function (Faker $faker) {

    $title = $faker->sentence;

    return [

        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'channel_id' => function() {
            return factory(Channel::class)->create()->id;
        },

        'title' => $title,
        'body'  => $faker->paragraph,
        'slug'  => Str::slug($title, '-'),

    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [

        'thread_id' => function() {
            return factory(Thread::class)->create()->id;
        },
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'body' => $faker->paragraph,
    ];
});

$factory->define(App\Channel::class, static function (Faker $faker) {

    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name,
    ];
});

$factory->define(
    DatabaseNotification::class, function (Faker $faker) {
    return [
        'id'            => Uuid::uuid4()->toString(),
        'type'          => ThreadWasUpdated::class,
        'notifiable_id' => static function () {
            return auth()->id() ?: factory(User::class)->create()->id;
        },
        'notifiable_type' => User::class,
        'data' => ['foo' => 'bar']
    ];
});

