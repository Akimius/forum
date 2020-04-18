<?php

namespace App\Inspections;

class KeyHeldDown extends AbstractInspection
{
    /**
     * @param string $body
     * @return bool
     * @throws \Exception
     */
    public function detect(string $body): bool
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Too many letters');
        }

        return false;
    }
}