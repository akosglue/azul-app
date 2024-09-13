<?php

declare(strict_types=1);

namespace App\Events;

use App\Player\Player;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WallTiledEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
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