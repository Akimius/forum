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

    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot()
    {

        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
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
     * Determine the path to the reply.
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

}
