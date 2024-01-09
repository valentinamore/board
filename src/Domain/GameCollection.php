<?php

declare(strict_types=1);

namespace App\Domain;

final class GameCollection
{
    private array $games;

    public function __construct(Game ...$games)
    {
        $this->games = $games;
    }

    public function getKey(Game $game): ?int
    {
        $result = null;
        foreach ($this->games as $key => $item) {
            if ($item->id === $game->id) {
                $result = $key;
                break;
            }
        }

        return $result;
    }

    /**
     * @return Game[]
     */
    public function getAll(): array
    {
        return $this->games;
    }

    /**
     * @return Game[]
     */
    public function getAllSorted(): array
    {
        $games = array_reverse($this->games);
        usort($games, function(Game $a, Game $b) {
            return $b->getTotalScoreResult() - $a->getTotalScoreResult();
        });

        return $games;
    }

    public function count(): int
    {
        return count($this->games);
    }

    public function add(Game $game): ?Game
    {
        if ($this->getKey($game) !== null) {
            return null;
        }

        $this->games[] = $game;

        return $game;
    }

    public function replace(Game $game): ?Game
    {
        if (($key = $this->getKey($game)) === null) {
            return null;
        }

        $this->games[$key] = $game;

        return $game;
    }

    public function remove(Game $game): ?Game 
    {
        $result = null;
        if (($key = $this->getKey($game)) === null) {
            return $result;
        }

        $result = $this->games[$key];
        unset($this->games[$key]);

        return $result;
    }
}
