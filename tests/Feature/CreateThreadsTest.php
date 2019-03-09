<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
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

        $thread = factory('App\Thread')->make();


        $response = $this->post('/threads', $thread->toArray());


        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);

    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()
            ->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->make($overrides);

        return $this->post('/threads', $thread->toArray());

    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 3])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function a_thread_can_be_deleted()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response =  $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [
            'id' => $thread->id
        ]);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

    }

    /** @test */
    function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        // User is not signed id

        $thread = factory(Thread::class)->create();

        $response =  $this->delete($thread->path());

        $response->assertRedirect('/login');


    }

}
