<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_have_profile()
    {
        $user = factory(User::class)->create();

        $this->get("/profiles/{$user->name}")
        ->assertSee($user->name);
    }

    /** @test */
    function profiles_display_all_threads_created_by_associated_user()
    {
        $user = factory(User::class)->create();

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);

        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
