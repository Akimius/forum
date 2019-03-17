<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $guarded = ['id'];

    public function subject()
    {
        return $this->morphTo();
    }
}
