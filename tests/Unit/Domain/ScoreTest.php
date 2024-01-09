<?php 

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Score;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ScoreTest extends TestCase
{
    private Score $score;

    public function setUp(): void
    {
        parent::setUp();

        $this->score = new Score();
    }

    public function testSetResultSuccess(): void
    {
        $this->assertSame(0, $this->score->getResult());

        $this->score->changeResult($newResult = 5);

        $this->assertSame($newResult, $this->score->getResult());
    }

    #[DataProvider('setResultErrorProvider')]
    public function testSetResultError(int $newResult, string $errorMessage): void
    {
        $this->score->changeResult(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->score->changeResult($newResult);
    }

    public static function setResultErrorProvider(): Generator
    {
        yield [-5, 'result needs be >= 0, given: -5'];
        yield [1, 'result needs be >= previous value, given: 1'];
    }
}
