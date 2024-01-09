<?php 

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Game;
use App\Domain\FinishedStatus;
use App\Domain\InProgressStatus;
use App\Domain\Score;
use DomainException;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    private string $id;
    private string $homeTeam;
    private string $awayTeam;
    private Game $game;

    public function setUp(): void
    {
        parent::setUp();

        $this->id = uniqid();
        $this->homeTeam = 'Mexico';
        $this->awayTeam = 'Canada';
        $this->game = new Game($this->id, $this->homeTeam, $this->awayTeam);
    }
    public function testGettersSuccess(): void
    {
        $this->assertSame($this->id, $this->game->id);
        $this->assertSame($this->homeTeam, $this->game->homeTeamName);
        $this->assertSame($this->awayTeam, $this->game->awayTeamName);
        $this->assertEquals(new Score(), $this->game->getHomeTeamScore());
        $this->assertEquals(new Score(), $this->game->getAwayTeamScore());
        $this->assertEquals(new InProgressStatus(), $this->game->getStatus());
    }

    public function testChangeStatusSuccess(): void
    {
        $this->game->changeStatus($statusTo = new FinishedStatus());

        $this->assertSame($statusTo, $this->game->getStatus());
    }

    public function testChangeStatusError(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Status changing not allowed from "IN_PROGRESS" to "IN_PROGRESS" status');

        $this->game->changeStatus(new InProgressStatus());
    }

    public function testUpdateScoresSuccess(): void
    {
        $this->game->getHomeTeamScore()->changeResult(3);
        $this->game->getAwayTeamScore()->changeResult(4);
        $newHomeTeamScore = $this->game->getHomeTeamScore();
        $newAwayTeamScore = $this->game->getAwayTeamScore();
        $this->game->updateScores($newHomeTeamScore, $newAwayTeamScore);

        $this->assertSame($newHomeTeamScore, $this->game->getHomeTeamScore());
        $this->assertSame($newAwayTeamScore, $this->game->getAwayTeamScore());
    }

    public function testUpdateScoresError(): void
    {
        $this->game->changeStatus(new FinishedStatus());

        $this->game->getHomeTeamScore()->changeResult(3);
        $this->game->getAwayTeamScore()->changeResult(4);
        $newHomeTeamScore = $this->game->getHomeTeamScore();
        $newAwayTeamScore = $this->game->getAwayTeamScore();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Modification not allowed for current status "FINISHED"');

        $this->game->updateScores($newHomeTeamScore, $newAwayTeamScore);
    }
}
