<?php

declare(strict_types=1);

namespace App\Events;

use App\Game\GameRound;
use App\Player\Player;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerFinishTurnEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	private Player $player;
	protected GameRound $round;

	public function __construct(Player $player, GameRound $round)
	{
		$this->player = $player;
		$this->round = $round;
	}

	public function getRound(): GameRound
	{
		return $this->round;
	}

	public function getPlayer(): Player
	{
		return $this->player;
	}
}