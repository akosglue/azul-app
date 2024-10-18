<?php

declare(strict_types=1);

namespace App\Player;

use App\Assert\Assert;
use Traversable;

/**
 * @implements \IteratorAggregate<Player>
 */
class PlayerCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var \SplStack<Player>
     */
    private \SplStack $players;

    /**
     * @param  array<Player>  $players
     */
    public function __construct(array $players = [])
    {
        $this->players = new \SplStack;
        Assert::allIsInstanceOf($players, Player::class);
        foreach ($players as $player) {
            $this->players->push($player);
        }
    }

    public function getIterator(): Traversable
    {
        return $this->players;
    }

    public function count(): int
    {
        return $this->players->count();
    }
}
