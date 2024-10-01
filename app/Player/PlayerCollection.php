<?php

declare(strict_types=1);

namespace App\Player;

use App\Assert\Assert;

/**
 * @method Player current()
 */
class PlayerCollection extends \SplStack
{
    public function __construct(array $players = [])
    {
        Assert::allIsInstanceOf($players, Player::class);
        foreach ($players as $player) {
            $this->push($player);
        }
    }
}
