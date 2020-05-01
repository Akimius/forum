<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Trending
     */
    private Trending $trending;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();

    }

    /** @test
     * @throws \JsonException
     */
    public function it_increments_a_threads_score_each_time_it_is_read(): void
    {
        $this->assertEmpty($this->trending->get());
        $thread = factory(Thread::class)->create();

        $this->call('GET', $thread->path()); // simulate reading of a thread

        $this->assertCount(1, $this->trending->get());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
