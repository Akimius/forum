<?php

namespace Tests\Feature;

use App\Reply;
use App\Rules\Recaptcha;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Channel;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    use MockeryPHPUnitIntegration;

    public function setUp(): void
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, static function () {
            return \Mockery::mock(Recaptcha::class, static function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function a_thread_requires_recaptcha_verification(): void
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    function guests_may_not_create_thread()
    {
        $this->withExceptionHandling();

        $this->post('threads')
            ->assertRedirect('/login');

        $this->get('threads/create')
            ->assertRedirect('/login');
    }

//    /** @test */
//    function a_user_can_create_new_forum_threads()
//    {
//        $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some body.']);
//
//        $this->get($response->headers->get('Location'))
//            ->assertSee('Some Title')
//            ->assertSee('Some body.');
//    }

//    public function publishThread($overrides = [])
//    {
//        $this->withExceptionHandling()
//            ->actingAs(factory('App\User')->create());
//
//        $thread = factory('App\Thread')->make($overrides);
//
//        return $this->post('/threads', $thread->toArray());
//
//    }

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 3])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug(): void
    {
        $this->markTestSkipped('Skipping just one recaptcha test');
        $this->signIn();

        $thread = create(Thread::class, ['title' => 'Foo Title']);

        $this->assertEquals('foo-title', $thread->slug);

        $thread = $this->postJson(route('threads.store'), array_merge($thread->toArray(),['g-recaptcha-response' => 'token']))->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response =  $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [
            'id' => $thread->id
        ]);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $thread->id,
            'subject_type'  => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $reply->id,
            'subject_type'  => get_class($reply)
        ]);

    }

    /** @test */
    function an_authorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        // User is not signed id
        $thread = factory(Thread::class)->create();

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->actingAs(factory(User::class)->create());
        $this->delete($thread->path())
            ->assertStatus(403);
    }

}
