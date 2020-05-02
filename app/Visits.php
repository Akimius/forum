<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var Thread
     */
    protected Thread $thread;

    /**
     * Visits constructor.
     * @param Thread $thread
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return $this
     */
    public function record(): Visits
    {
        Redis::incr($this->cacheKeys());

        return $this; // In case we want to continue chaining
    }


    /**
     * @return mixed
     */
    public function reset()
    {
        Redis::del($this->cacheKeys());

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return Redis::get($this->cacheKeys()) ?? 0;
    }

    /**
     * @return string
     */
    protected function cacheKeys(): string
    {
        return "threads.{$this->thread->id}.visits";
    }
}