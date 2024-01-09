<?php

declare(strict_types=1);

namespace App\Domain;

enum StatusTypeEnum: string
{
    case InProgress = 'IN_PROGRESS';
    case Finished = 'FINISHED';
}
