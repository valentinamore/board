<?php 

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Board;
use App\Domain\FinishedStatus;
use App\Domain\Game;
use App\Domain\GameCollection;
use App\Domain\Score;
use DomainException;
use PHPUnit\Framework\TestCase;

final class BoardTest extends TestCase
{
    public function testGetSummaryEmptyBoardSuccess(): void
    {
        $board = new Board(uniqid());

        self::assertEquals([], $board->getSummary());
    }

    public function testGetSummarySuccess(): void
    {
        $board = new Board(uniqid());

        $games = [];
        $gamesData = [
            ['Mexico', 'Canada', 0, 5],
            ['Spain', 'Brazil', 10, 2],
            ['Germany', 'France', 2, 2],
            ['Uruguay', 'Italy', 6, 6],
            ['Argentina', 'Australia', 3, 1],
        ];

        foreach ($gamesData as $data) {
            $game = new Game(uniqid(), $data[0], $data[1]);
            $homeTeamScore = new Score();
            $homeTeamScore->changeResult($data[2]);
            $awayTeamScore = new Score();
            $awayTeamScore->changeResult($data[3]);
            $game->updateScores($homeTeamScore, $awayTeamScore);
            $board->addGame($game);
            $games[] = $game;
        }

        self::assertEquals(
            [$games[3], $games[1], $games[0], $games[4], $games[2]],
            $board->getSummary()
        );
    }

    public function testAddGameSuccess(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $addedGame = $board->addGame($game);

        self::assertSame($game, $addedGame);
        self::assertEquals([$game], $board->getSummary());
    }

    public function testAddGameError(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $game->changeStatus(new FinishedStatus());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Game needs to be in progress');

        $board->addGame($game);
    }

    public function testAddGameExisted(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
    
        $game = $board->addGame($game);
        self::assertEquals([$game], $board->getSummary());
        
        $game = $board->addGame($game);
        self::assertNull($game);
    }

    public function testUpdateGameSuccess(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $board->addGame($game);

        $score = new Score();
        $score->changeResult(4);
        $game->updateScores($score, $score);

        $updatedGame = $board->updateGame($game);

        self::assertSame($game, $updatedGame);
        self::assertEquals([$game], $board->getSummary());
    }

    public function testUpdateGameError(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $board->addGame($game);
        $game->changeStatus(new FinishedStatus());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Game needs to be in progress');

        $board->updateGame($game);
    }

    public function testUpdateGameUnknown(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $updatedGame = $board->updateGame($game);

        self::assertNull($updatedGame);
    }

    public function testFinishGameSuccess(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $board->addGame($game);
        $finishedGame = $board->finishGame($game);

        self::assertSame($game, $finishedGame);
        self::assertEquals([], $board->getSummary());
    }

    public function testFinishGameError(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $board->addGame($game);
        $game->changeStatus(new FinishedStatus());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Game needs to be in progress');

        $board->finishGame($game);
    }

    public function testFinishGameUnknown(): void
    {
        $board = new Board(uniqid());
        $game = new Game(uniqid(), 'Mexico', 'Canada');
        $finishedGame = $board->finishGame($game);

        self::assertNull($finishedGame);
    }
}
