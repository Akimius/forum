<?php

namespace App;

use App\Events\ThreadReceivedNewReplyEvent;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Redis;
use phpDocumentor\Reflection\Types\This;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var string[]
     */
    protected $with = ['owner', 'channel'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isSubscribedTo'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // no need after adding column

//        static::addGlobalScope('replyCount', function ($builder) {
//            $builder->withCount('replies');
//        });

        static::deleting(function($thread) {
            $thread->replies->each->delete();
        });

    }


    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
//            ->withCount('favorites')
//            ->with('owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReplyEvent($this, $reply));

        return $reply;

    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);

    }

    /**
     * @return int
     */
    public function getReplyCountAttribute()
    {
        return $this->replies()->count();

    }

    /**
     * @param null $userId
     * @return $this
     */
    public function subscribe($userId = null): Thread
    {
        $this->subscriptions()
            ->create([
                'user_id' => $userId ?: auth()->id(),
            ]);

        return $this;
    }

    /**
     * @param null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
       return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Determine if the current user is subscribed to the thread.
     *
     * @return boolean
     */
    public function getIsSubscribedToAttribute(): bool
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists(); // if the record exists
    }

    /**
     * Determine if the thread has been updated since the user last read it.
     *
     * @param User|null $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor(User $user): bool
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    /**
     * @return Visits
     */
    public function visits(): Visits
    {
        return new Visits($this);
    }
}
