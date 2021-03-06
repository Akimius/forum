<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreadSubscription extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notify($reply): void
    {
        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
