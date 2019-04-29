<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function guests_cannot_favorite_anything()
    {
        $this->withExceptionHandling()

            ->post('replies/1/favorites')
            ->assertRedirect('login');

    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory('App\Reply')->create(); // thread will be created automatically see the factory

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory('App\Reply')->create(); // thread will be created automatically see the factory

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');
        $this->assertCount(0, $reply->favorites);
    }


    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory('App\Reply')->create(); // thread will be created automatically see the factory

        $this->post('replies/' . $reply->id . '/favorites');
        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);

    }
}
