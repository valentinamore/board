<?php

declare(strict_types=1);

namespace App\Domain;

final class FinishedStatus extends Status
{
    public static function getType(): StatusTypeEnum
    {
        return StatusTypeEnum::Finished;
    }

    public function isAllowedModify(): bool
    {
        return false;
    }
}
