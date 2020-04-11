<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_not_by_current_user(): void
    {
        $this->signIn();

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
        $this->signIn();

        $thread = factory(Thread::class)->create()->subscribe();

        $thread->addReply(
            [
                'user_id' => factory(User::class)->create()->id,
                'body'    => 'Some reply here'
            ]
        );

        $user = auth()->user();

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);
    }


    /** @test */
    public function a_user_can_clear_notification(): void
    {
        $this->signIn();

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
}
