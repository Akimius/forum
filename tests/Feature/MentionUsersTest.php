<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified(): void
    {
        $john = factory(User::class)->create(['name' => 'JohnDoe']);

        $this->signIn($john);

        $janeDoeName = 'JaneDoe';
        $jane = factory(User::class)->create(['name' => $janeDoeName]);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->create(
            [
                'body' => "@{$janeDoeName} look at this. And also @FrankDoe",
            ]
        );

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }
}
