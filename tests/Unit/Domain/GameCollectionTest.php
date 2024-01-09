<?php 

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Game;
use App\Domain\GameCollection;
use App\Domain\Score;
use PHPUnit\Framework\TestCase;

final class GameCollectionTest extends TestCase
{
    private array $games;
    private GameCollection $gameCollection;

    public function setUp(): void
    {
        parent::setUp();

        $this->games = [
            new Game(uniqid(), 'Mexico', 'Canada'),
            new Game(uniqid(), 'Spain', 'Brazil'),
            new Game(uniqid(), 'Germany', 'France'),
        ];
        
        $this->gameCollection = new GameCollection(...$this->games);
    }

    public function testGetAllEmptySuccess(): void
    {
        $gameCollection = new GameCollection(...[]);

        self::assertSame([], $gameCollection->getAll());
    }

    public function testGetAllSuccess(): void
    {
        self::assertSame($this->games, $this->gameCollection->getAll());
    }

    public function testGetKeySuccess(): void
    {
        $expected = $this->games[1];
        $key = $this->gameCollection->getKey($expected);

        self::assertNotNull($key);
        self::assertSame($expected, $this->gameCollection->getAll()[$key]);
    }

    public function testGetKeyNullSuccess(): void
    {
        $key = $this->gameCollection->getKey(new Game(uniqid(), 'Germany', 'France'));

        self::assertNull($key);
    }

    public function testCountSuccess(): void
    {
        self::assertSame(count($this->games), $this->gameCollection->count());
    }

    public function testAddSuccess(): void
    {
        $gameToAdd = new Game(uniqid(), 'Germany', 'France');
        $result = $this->gameCollection->add($gameToAdd);

        self::assertSame($gameToAdd, $result);
        self::assertSame(4, $this->gameCollection->count());
        self::assertSame(array_merge($this->games, [$gameToAdd]), $this->gameCollection->getAll());
    }

    public function testAddTwiceSuccess(): void
    {
        $gameToAdd = new Game(uniqid(), 'Germany', 'France');

        $result = $this->gameCollection->add($gameToAdd);
        self::assertSame($gameToAdd, $result);

        $result = $this->gameCollection->add($gameToAdd);
        self::assertNull($result);

        self::assertSame(4, $this->gameCollection->count());
        self::assertSame(array_merge($this->games, [$gameToAdd]), $this->gameCollection->getAll());
    }

    public function testReplaceSuccess(): void
    {
        $gameToReplace = $this->games[2];
        $score = new Score();
        $score->changeResult(3);
        $gameToReplace->updateScores($score, $score);
        $result = $this->gameCollection->replace($gameToReplace);
    
        self::assertSame($gameToReplace, $result);
        self::assertSame(3, $this->gameCollection->count());
        self::assertSame([$this->games[0], $this->games[1], $gameToReplace], $this->gameCollection->getAll());
    }

    public function testReplaceUnknown(): void
    {
        $gameToReplace = new Game(uniqid(), 'Germany', 'France');
        $result = $this->gameCollection->replace($gameToReplace);

        self::assertNull($result);
        self::assertSame(3, $this->gameCollection->count());
        self::assertSame($this->games, $this->gameCollection->getAll());
    }

    public function testRemoveSuccess(): void
    {
        $gameToRemove = $this->games[2];
        $result = $this->gameCollection->remove($gameToRemove);

        self::assertSame($gameToRemove, $result);
        self::assertSame(2, $this->gameCollection->count());
        self::assertSame([$this->games[0], $this->games[1]], $this->gameCollection->getAll());
    }

    public function testRemoveUnknown(): void
    {
        $gameToRemove = new Game(uniqid(), 'Germany', 'France');
        $result = $this->gameCollection->remove($gameToRemove);

        self::assertNull($result);
        self::assertSame(3, $this->gameCollection->count());
        self::assertSame($this->games, $this->gameCollection->getAll());
    }
}
