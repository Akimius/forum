<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user  = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /**
     * @test
     */
    public function a_user_can_determine_their_avatar_path(): void
    {
        $user = factory(User::class)->create();
        $this->assertEquals('/avatars/default.jpg', $user->avatar());

        $user = factory(User::class)->create(['avatar_path' => '/avatars/me.jpg']);
        $this->assertEquals('/avatars/me.jpg', $user->avatar());
    }
}
