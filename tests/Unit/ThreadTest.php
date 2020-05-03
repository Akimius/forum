<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Channel;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected Thread $thread;
    protected array $addReply;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();

        $this->addReply =   [
            'body'    => 'Foobar',
            'user_id' => factory(User::class)->create()->id
        ];
    }

    /**
     * @test
     */
    public function a_thread_has_replies(): void
    {

        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /**
     * @test
     */
    public function a_thread_has_a_creator(): void
    {

        $this->assertInstanceOf(User::class, $this->thread->owner);
    }

    /**
     * @test
     */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply($this->addReply);

        $this->assertCount(1, $this->thread->replies);
    }

    /**
     * @test
     */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = factory(Thread::class)->create();

        $this->assertInstanceOf(Channel::class, $thread->channel);

    }

    /**
     * @test
     */
    public function a_thread_has_a_path(): void
    {
        $thread = factory(Thread::class)->create();

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->slug, $thread->path());
    }

    /**
     * @test
     */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = factory(Thread::class)->create();

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /**
     * @test
     */
    public function a_thread_can_be_unsubscribed_from(): void
    {
        $thread = factory(Thread::class)->create();

        $thread->subscribe($userId = 1);
        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it(): void
    {
        $thread = factory(Thread::class)->create();

        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added(): void
    {
        Notification::fake();

        $this
            ->signIn()
            ->thread
            ->subscribe()
            ->addReply($this->addReply);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = $this->thread;

        tap(auth()->user(), function ($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    public function a_thread_records_each_visit(): void
    {
        $thread = factory(Thread::class)->make(['id' => 1]);

        $thread->visits()->reset();

        $thread->visits()->record();
        $this->assertSame(1, (int)$thread->visits()->count());

        $thread->visits()->record();
        $this->assertSame(2, (int)$thread->visits()->count());
    }
}
