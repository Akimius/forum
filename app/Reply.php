<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = ['id'];

    protected $with = ['owner', 'favorites'];

    protected $withCount = [];

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    protected static function boot()
    {

        parent::boot();

        static::created(static function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(static function ($reply) {
            // 1st option at PHP level
            if ($reply->isBest()) {
                $reply->thread->update(['best_reply_id' => null]);
            }
            $reply->thread->decrement('replies_count');
        });
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');

    }

    /**
     * A reply belongs to a thread.
     *
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished(): bool
    {
        return $this->created_at->gt(now()->subMinutes(1));
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);
        return $matches[1];
    }

    /**
     * Determine the path to the reply.
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    /**
     * Set the body attribute.
     *
     * @param string $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace(
            '/@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>',
            $body
        );
    }

    /**
     *
     *
     * @return bool
     */
    public function isBest(): bool
    {
        return (int)$this->thread->best_reply_id === $this->id;
    }

    /**
     *
     *
     * @return bool
     */
    public function getIsBestAttribute(): bool
    {
        return $this->isBest();
    }
}
