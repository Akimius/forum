<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_thread()
    {
        $this->withExceptionHandling();

        $this->post('threads')
            ->assertRedirect('/login');

        $this->get('threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_may_create_new_forum_thread()
    {

        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create();


        $this->post('/threads', $thread->toArray());

        $response = $this->get($thread->path());


        $response->assertSee($thread->title)
                ->assertSee($thread->body);

    }
}
