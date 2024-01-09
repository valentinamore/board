<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\GameCollection;
use DomainException;

final class Board
{
    private GameCollection $games;

    public function __construct(public readonly string $id)
    {
        $this->games = new GameCollection();
    }

    public function addGame(Game $game): ?Game 
    {
        $this->validateGame($game);

        return $this->games->add($game);
    }

    public function updateGame(Game $game): ?Game
    {
        $this->validateGame($game);

        return $this->games->replace($game);
    }

    public function finishGame(Game $game): ?Game
    {
        $this->validateGame($game);

        return $this->games->remove($game);
    }

    /**
     * @return Game[]
     */
    public function getSummary(): array
    {
        return $this->games->getAllSorted();
    }

    /**
     * @throws DomainException
     */
    private function validateGame(Game $game): void
    {
        if ($game->isInProgress() !== true) {
            throw new DomainException('Game needs to be in progress');
        }
    }
}
