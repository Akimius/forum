<?php

namespace App\Inspections;

abstract class AbstractInspection
{
    public const BASE_INVALID_KEYS = [
        'some spam expression here'
    ];

    /**
     * @param string $body
     * @return bool
     */
    abstract public function detect(string $body): bool;
}