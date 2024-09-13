<?php

declare(strict_types=1);

namespace App\Game;

use App\Events\PlayerFinishTurnEvent;
use App\Events\RoundCreatedEvent;
use App\Events\WallTiledEvent;
use App\Player\PlayerCollection;
use App\Tile\Marker;

class Game
{
	private Bag $bag;
	private ?GameRound $round = null;

	public function __construct(Bag $bag)
	{
		$this->bag = $bag;
	}

	public function play(PlayerCollection $players): void
	{
		while (true) {
			if (!$this->round) {
				$this->round = $this->createRound($players);
				RoundCreatedEvent::dispatch($this->round);
			}
			if ($this->round->canContinue()) {
				$table = $this->round->getTable();
				foreach ($players as $player) {
					$move = $player->getNextMove(
						$this->round->getFactories(),
						$table
					);
					if (!$move) {
						continue;
					}
					if ($move->isFromTable() && $table->hasMarker()) {
						$player->takeMarker($table->takeMarker());
					}
					$storage = $move->getStorage();
					$tiles = $storage->take($move->getColor());
					$player->placeTiles($tiles, $move->getRowNumber());
					if (!$move->isFromTable()) {
						$table->addToCenterPile($storage->takeAll());
					}
					PlayerFinishTurnEvent::dispatch($player, $this->round);
				}
			} else {
				$this->round = null;
				foreach ($players as $player) {
					$player->doWallTiling();
					WallTiledEvent::dispatch($player);
					$this->bag->discardTiles($player->discardTiles());
					if ($player->isGameOver()) {
						// TODO rework game cycle, round could end at each turn, game over on each turn
						return;
					}
				}
			}
		}
	}

	private function createRound(PlayerCollection $players): GameRound
	{
		$table = new Table(new Marker());
		return new GameRound(
			$table,
			[
				new Factory($this->bag->getNextPlate()),
				new Factory($this->bag->getNextPlate()),
				new Factory($this->bag->getNextPlate()),
				new Factory($this->bag->getNextPlate()),
				new Factory($this->bag->getNextPlate()),
			]
		);
	}
}