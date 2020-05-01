<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public const REDIS_KEY      = 'trending_threads';
    public const REDIS_TEST_KEY = 'testing_trending_threads';
    public const MAX_TOP        = 5;

    /**
     * @return array
     */
    public function get(): array
    {
        // 0-4 = Top 5 items
       return array_map('json_decode', Redis::zrevrange($this->cacheKeys(), 0, (self::MAX_TOP - 1)));
    }

    /**
     * @param Thread $thread
     * @throws \JsonException
     */
    public function push(Thread $thread): void
    {

        Redis::zincrby(
            $this->cacheKeys(),
            1,
            json_encode(
                [
                    'title' => $thread->title,
                    'path' => $thread->path()
                ],
                JSON_THROW_ON_ERROR
            )
        );
    }

    /**
     * @return string
     */
    public function cacheKeys(): string
    {
        return app()->environment('testing')
            ? static::REDIS_TEST_KEY
            : static::REDIS_KEY;
    }

    /**
     *
     */
    public function reset(): void
    {
        Redis::del($this->cacheKeys());
    }

}