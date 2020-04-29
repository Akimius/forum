<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Redis::del('trending_threads');
    }

    /** @test
     * @throws \JsonException
     */
    public function it_increments_a_threads_score_each_time_it_is_read(): void
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));
        $thread = factory(Thread::class)->create();

        $this->call('GET', $thread->path()); // simulate reading of a thread

        $this->assertCount(1, Redis::zrevrange('trending_threads', 0, -1));

        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);

        //$this->assertEquals($thread->title, json_decode($trending[0]->title));

    }
}
