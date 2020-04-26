<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /**
     * @test
     */
    public function a_user_can_view_all_threads()
    {

        $this->get('/threads')
            ->assertSee($this->thread->title);

    }

    /**
     * @test
     */
    public function a_user_can_read_a_single_thread()
    {

        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_read_replies_associated_with_thread()
    {

        $reply = factory('App\Reply')
            ->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_according_to_channel()
    {
        $channel = factory('App\Channel')->create();

        $threadInChannel = factory('App\Thread')->create(['channel_id' => $channel->id ]);
        $threadNotInChannel = factory('App\Thread')->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);

    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->be($user = factory('App\User')
            ->create(['name' => 'JohnDoe']));

        $threadByJohn = factory('App\Thread')->create(['user_id' => auth()->id() ]);
        $threadNotByJohn = factory('App\Thread')->create();

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);


    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_by_popularity()
    {

        $threadWithTwoReplies = factory('App\Thread')->create();
        factory('App\Reply', 2)->create(['thread_id' => $threadWithTwoReplies->id ]);

        $threadWithThreeReplies = factory('App\Thread')->create();
        factory('App\Reply', 3)->create(['thread_id' => $threadWithThreeReplies->id ]);

        $threadsWithNoReplies = $this->thread; // when triggering parent setup

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));

    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {

        $thread = factory(Thread::class)->create();

        factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);

    }


    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = factory('App\Thread')->create();
        factory('App\Reply', 2)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

}
