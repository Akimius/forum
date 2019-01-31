<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

//    /** @test */
//    function guests_may_not_create_thread()
//    {
//        $this->withExceptionHandling();
//        $this->get('/threads/create')
//            ->assertRedirect(route('login'));
//        $this->post(route('threads'))
//            ->assertRedirect(route('login'));
//
//    }

    /** @test */
    function an_authenticated_user_may_create_new_forum_thread()
    {

        // Given we have a signed in user
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());

        $response = $this->get($thread->path());


        $response->assertSee($thread->title)
                ->assertSee($thread->body);

    }
}
