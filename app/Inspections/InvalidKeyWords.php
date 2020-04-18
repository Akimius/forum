<?php

namespace App\Inspections;

class InvalidKeyWords extends AbstractInspection
{
    protected array $invalidKeyWords = [
        'yahoo customer support'
    ];

    public function __construct()
    {
        $this->invalidKeyWords = array_merge(
            $this->invalidKeyWords,
            self::BASE_INVALID_KEYS
        );
    }

    /**
     * @param string $body
     * @return bool
     * @throws \Exception
     */
    public function detect(string $body): bool
    {
        $this->detectInvalidKeyWords($body);

        return false;
    }

    /**
     * @param string $body
     * @throws \Exception
     */
    protected function detectInvalidKeyWords(string $body): void
    {
        foreach ($this->invalidKeyWords as $keyWord) {
            if (stripos($body, $keyWord) !== false) {
                throw new \Exception('Spam detected');
            }
        }
    }

}