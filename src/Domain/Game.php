<?php

declare(strict_types=1);

namespace App\Domain;

use DomainException;

final class Game
{
    private Score $homeTeamScore;

    private Score $awayTeamScore;

    private Status $status;

    public function __construct(
        public readonly string $id,
        public readonly string $homeTeamName,
        public readonly string $awayTeamName
    ) {
        $this->homeTeamScore = new Score();
        $this->awayTeamScore = new Score();
        $this->status = new InProgressStatus();
    }

    public function getHomeTeamScore(): Score
    {
        return $this->homeTeamScore;
    }

    public function getAwayTeamScore(): Score
    {
        return $this->awayTeamScore;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getTotalScoreResult(): int
    {
        return $this->homeTeamScore->getResult() + $this->awayTeamScore->getResult();
    }

    /**
     * @throws DomainException
     */
    public function changeStatus(Status $status): void
    {
        $this->status->ensureAllowedChangeTo($status);

        $this->status = $status;
    }

    /**
     * @throws DomainException
     */
    public function updateScores(Score $homeTeamScore, Score $awayTeamScore): void
    {
        $this->status->ensureAllowedModify();

        $this->homeTeamScore = $homeTeamScore;
        $this->awayTeamScore = $awayTeamScore;
    }

    public function isInProgress(): bool
    {
        return $this->status->getType() === InProgressStatus::getType();
    }
}
