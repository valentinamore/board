<?php

declare(strict_types=1);

namespace App\Domain;

use DomainException;

abstract class Status
{
    protected array $nextStatuses = [];

    public abstract static function getType(): StatusTypeEnum;

    public abstract function isAllowedModify(): bool;

    public function isAllowedChangeTo(Status $status): bool
    {
        return in_array(get_class($status), $this->nextStatuses, true);
    }

    /**
     * @throws DomainException
     */
    public function ensureAllowedChangeTo(Status $status): void
    {
        if (!$this->isAllowedChangeTo($status)) {
            throw new DomainException(
                sprintf(
                    'Status changing not allowed from "%s" to "%s" status',
                    $this->getType()->value,
                    $status->getType()->value,
                )
            );
        }
    }

    /**
     * @throws DomainException
     */
    public function ensureAllowedModify(): void
    {
        if (!$this->isAllowedModify()) {
            throw new DomainException(
                sprintf(
                    'Modification not allowed for current status "%s"',
                    $this->getType()->value
                )
            );
        }
    }
}
