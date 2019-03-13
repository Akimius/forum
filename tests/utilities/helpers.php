<?php

use App\User;

/**
 * Created by PhpStorm.
 * User: akim
 * Date: 13.03.19
 * Time: 22:22
 */

function signIn($class, $attributes = [])
{
    return factory($class)->create($attributes);
}