<?php

namespace App\Inspections;

class Spam
{
    protected array $inspections = [
        InvalidKeyWords::class,
        KeyHeldDown::class
    ];

    /**
     * @param string $body
     * @return bool
     * @throws \Exception
     */
    public function detect(string $body): bool
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}
