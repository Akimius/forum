<?php

namespace Tests\Feature;

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

        $reply = factory('App\Reply')->create(); // thread will be created automatically see the factory

        $this->post('replies' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);

    }
}
