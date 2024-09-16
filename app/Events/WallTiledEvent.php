<?php

declare(strict_types=1);

namespace App\Events;

use App\Player\Player;

class WallTiledEvent extends GameEvent
{
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }
}
