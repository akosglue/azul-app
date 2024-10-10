<?php

declare(strict_types=1);

namespace App\Player;

use App\Assert\Assert;

/**
 * @method Player current()
 */

/** @extends \SplStack<Player> */
class PlayerCollection extends \SplStack
{
    /**
     * @param  array<Player>  $players
     */
    public function __construct(array $players = [])
    {
        Assert::allIsInstanceOf($players, Player::class);
        foreach ($players as $player) {
            $this->push($player);
        }
    }
}
