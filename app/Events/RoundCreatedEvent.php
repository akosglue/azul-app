<?php

declare(strict_types=1);

namespace App\Events;

use App\Game\GameRound;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	protected GameRound $round;

	public function __construct(GameRound $round)
	{
		$this->round = $round;
	}

	public function getRound(): GameRound
	{
		return $this->round;
	}
}