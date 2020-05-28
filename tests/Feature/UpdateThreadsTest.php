<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();
        $this->signIn();
    }

    /** @test */
    public function unauthorized_users_may_not_update_threads(): void
    {
        $thread = factory(Thread::class)->create(['user_id' => create(User::class)->id]);

        $this->patch($thread->path(), [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated(): void
    {
        $thread = factory(Thread::class)->create(['user_id' => create(User::class)->id]);

        $this->patch($thread->path(), ['title' => 'title updated'])->assertStatus(403);
        $this->patch($thread->path(), ['body'  => 'body updated'])->assertStatus(403);
    }

    /** @test */
    public function a_thread_can_be_updated_by_its_creator(): void
    {
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title'  => 'Changed',
            'body'   => 'Changed body',
        ]);

        $thread = $thread->fresh();

        $this->assertEquals('Changed', $thread->title);
        $this->assertEquals('Changed body', $thread->body);
    }
}
