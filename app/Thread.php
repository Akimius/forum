<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['owner', 'channel'];

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


    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
//            ->withCount('favorites')
//            ->with('owner');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {

        return $this->replies()->create($reply);

    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);

    }

    public function getReplyCountAttribute()
    {
        return $this->replies()->count();

    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()
            ->create([
                'user_id' => $userId ?: auth()->id(),
            ]);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
       return $this->hasMany(ThreadSubscription::class);
    }

}
