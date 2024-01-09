<?php

declare(strict_types=1);

namespace App\Domain;

final class InProgressStatus extends Status
{
    protected array $nextStatuses = [FinishedStatus::class];

    public static function getType(): StatusTypeEnum
    {
        return StatusTypeEnum::InProgress;
    }

    public function isAllowedModify(): bool
    {
        return true;
    }
}
