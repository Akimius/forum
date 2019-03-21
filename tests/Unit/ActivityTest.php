<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_records_activity_when_a_thread_is_created()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread',
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory(Reply::class)->create(); // Thread will be created as well, see the model factory

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
        $this->actingAs(factory(User::class)->create());

        factory(Thread::class, 2)->create([
            'user_id' => auth()->id()
        ]);

        auth()->user()->activity()->first()->update([
            'created_at' => Carbon::now()->subWeek()
        ]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }

}
