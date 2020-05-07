<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply(): void
    {
        $this->signIn();

        $thread = create(Thread::class);

        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best(): void
    {
        //$this->withExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);


        $this->signIn(create(User::class));

        $this->postJson(route('best-replies.store', [$replies[1]->id]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
