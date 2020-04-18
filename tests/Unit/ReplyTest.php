<?php

namespace Tests\Unit;

use App\User;
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
    public function it_has_an_owner()
    {
        $reply = factory(Reply::class)->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
