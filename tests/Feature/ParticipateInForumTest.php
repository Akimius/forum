<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\User;
use App\Thread;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies(): void
    {
        $this->withExceptionHandling();

        $this->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads(): void
    {
        $this->be($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make();


        $this->post($thread->path() . '/replies', $reply->toArray());

//        $this->get($thread->path())
//            ->assertSee($reply->body);
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);

        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body(): void
    {
        $this->withExceptionHandling()
            ->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make(['body' => null]);

        $response = $this->postJson($thread->path() . '/replies', $reply->toArray());

        //$response->assertSessionHasErrors('body'); // Not working
        $response->assertStatus(422); // "Unprocessable Entity"
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies(): void
    {
        $this->withExceptionHandling();

        $reply = factory(Reply::class)->create();

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies(): void
    {
        $this->signIn();

        $reply = factory(Reply::class)->create([
            'user_id' => auth()->id()
        ]);

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies(): void
    {
        $this->withExceptionHandling();

        $reply = factory(Reply::class)->create();

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies(): void
    {
        $this->signIn();

        $reply = factory(Reply::class)->create([
            'user_id' => auth()->id()
        ]);

        $updatedReply = 'You been changed, fool';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    public function replies_that_contain_span_may_not_be_created(): void
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make(
            [
                'body' => 'Yahoo Customer Support'
            ]
        );

        $this->postJson($thread->path() . '/replies', $reply->toArray())
        ->assertStatus(422);
    }

    /** @test */
//    public function users_may_only_reply_a_maximum_of_once_per_minute(): void
//    {
//        $this->markTestSkipped('Throttling skipped');
//
//        $this->withExceptionHandling();
//
//        $this->signIn();
//
//        $thread = create(Thread::class);
//        $reply = make(Reply::class);
//
//        $this->post($thread->path() . '/replies', $reply->toArray())
//            ->assertStatus(201);
//
//        $this->post($thread->path() . '/replies', $reply->toArray())
//            ->assertStatus(429);
//    }
}
