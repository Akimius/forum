<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function($model){
            $model->favorites->each->delete();
        });

    }

    /**
     * Get the number of favorites for the reply.
     *
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');

    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        // 1st option
//        $this->favorites()
//            ->where($attributes)
//            ->get()
//            ->each(function ($favorite) {
//            $favorite->dalete();
//        });

        // 2nd option
        $this->favorites()
            ->where($attributes)
            ->get()->each->delete();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

}