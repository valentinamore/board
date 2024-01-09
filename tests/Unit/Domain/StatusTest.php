<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\StatusTypeEnum;
use Generator;
use App\Domain\FinishedStatus;
use App\Domain\InProgressStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
    #[DataProvider('getNameSuccessProvider')]
    public function testGetNameSuccess(StatusTypeEnum $statusType, string $statusClassName): void
    {
       $this->assertSame($statusType, (new $statusClassName())->getType());
    }

    #[DataProvider('isAllowedModifySuccessProvider')]
    public function testIsAllowedModifySuccess(bool $result, string $statusClassName): void
    {
       $this->assertSame($result, (new $statusClassName())->isAllowedModify());
    }

    #[DataProvider('isAllowedChangeToSuccessProvider')]
    public function testIsAllowedChangeToSuccess(
        bool $result,
        string $statusClassNameFrom,
        string $statusClassNameTo
    ): void {
        $statusFrom = new $statusClassNameFrom();
        $statusTo = new $statusClassNameTo();

        $this->assertSame($result, $statusFrom->isAllowedChangeTo($statusTo));
    }

    public static function getNameSuccessProvider(): Generator
    {
        yield [StatusTypeEnum::InProgress, InProgressStatus::class];
        yield [StatusTypeEnum::Finished, FinishedStatus::class];
    }

    public static function isAllowedModifySuccessProvider(): Generator
    {
        yield [true, InProgressStatus::class];
        yield [false, FinishedStatus::class];
    }

    public static function isAllowedChangeToSuccessProvider(): Generator
    {
        yield [true, InProgressStatus::class, FinishedStatus::class];
        yield [false, InProgressStatus::class, InProgressStatus::class];
        yield [false, FinishedStatus::class, InProgressStatus::class];
        yield [false, FinishedStatus::class, FinishedStatus::class];
    }
}
