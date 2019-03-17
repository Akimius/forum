<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = ['id'];

    protected $with = ['owner', 'favorites'];

    protected $withCount = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');

    }

}
