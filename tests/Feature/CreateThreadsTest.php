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
    function authorized_users_can_delete_threads()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response =  $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [
            'id' => $thread->id
        ]);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $thread->id,
            'subject_type'  => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $reply->id,
            'subject_type'  => get_class($reply)
        ]);

    }

    /** @test */
    function an_authorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        // User is not signed id
        $thread = factory(Thread::class)->create();

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->actingAs(factory(User::class)->create());
        $this->delete($thread->path())
            ->assertStatus(403);
    }

}
