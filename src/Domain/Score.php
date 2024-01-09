<?php

declare(strict_types=1);

namespace App\Domain;

use InvalidArgumentException;

final class Score
{
    private int $result;
    
    public function __construct()
    {
        $this->result = 0;
    }

    public function getResult(): int
    {
        return $this->result;
    }

    public function changeResult(int $result): void
    {
        $this->validateResult($result);

        $this->result = $result;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateResult(int $result): void
    {
        if ($result < 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'result needs be >= 0, given: %d',
                    $result
                )
            );
        }

        if ($this->result > $result) {
            throw new InvalidArgumentException(
                sprintf(
                    'result needs be >= previous value, given: %d',
                    $result
                )
            );
        }
    }
}
