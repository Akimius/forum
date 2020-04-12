<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_not_by_current_user(): void
    {

        $thread = factory(Thread::class)->create();

        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply(
            [
                'user_id' => auth()->id(),
                'body'    => 'Some reply here'
            ]
        );

         $this->assertCount(0, auth()->user()->fresh()->notifications);


        $thread->addReply(
            [
                'user_id' => factory(User::class)->create()->id,
                'body'    => 'Some reply here'
            ]
        );

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notification(): void
    {

        factory(DatabaseNotification::class)->create();

        $this->assertCount(
            1,
            $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json()
        );
    }


    /** @test */
    public function a_user_can_clear_notification(): void
    {

        $thread = factory(Thread::class)->create()->subscribe();

        $thread->addReply(
            [
                'user_id' => factory(User::class)->create()->id,
                'body'    => 'Some reply here'
            ]
        );

        $user = auth()->user();

        $this->assertCount(1, $user->fresh()->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);

    }

    /** @test */
    public function a_user_can_mark_notification_as_read(): void
    {
        factory(DatabaseNotification::class)->create();

        tap(auth()->user(), function ($user) {

            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

            $this->assertCount(0, $user->fresh()->unreadNotifications);

        });


    }
}
