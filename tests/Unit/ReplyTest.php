<?php

namespace Tests\Unit;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function it_has_an_owner(): void
    {
        $reply = factory(Reply::class)->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published(): void
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_users_in_a_body(): void
    {
        $reply = create(Reply::class, [
            'body' => '@JohnDoe wants to talk to @JaneDoe'
        ]);

        $this->assertEquals(['JohnDoe', 'JaneDoe'], $reply->mentionedUsers());

    }
}
