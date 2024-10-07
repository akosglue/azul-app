<?php

declare(strict_types=1);

namespace App\Game;

use App\Events\GameEvent;
use App\Events\PlayerFinishTurnEvent;
use App\Events\RoundCreatedEvent;
use App\Events\WallTiledEvent;
use App\Player\PlayerCollection;
use App\Tile\Marker;
use Illuminate\Events\Dispatcher;

class Game
{
    private Bag $bag;

    private ?GameRound $round = null;

    private Dispatcher $dispatcher;

    public function __construct(Bag $bag, Dispatcher $dispatcher)
    {
        $this->bag = $bag;
        $this->dispatcher = $dispatcher;
    }

    public function play(PlayerCollection $players): void
    {
        while (true) {
            if (! $this->round) {
                $this->round = $this->createRound($players);
                $this->dispatch(new RoundCreatedEvent($this->round));
            }
            if ($this->round->canContinue()) {
                $table = $this->round->getTable();
                foreach ($players as $player) {
                    $move = $player->getNextMove(
                        $this->round->getFactories(),
                        $table
                    );
                    if (! $move) {
                        continue;
                    }
                    if ($move->isFromTable() && $table->hasMarker()) {
                        $player->takeMarker($table->takeMarker());
                    }
                    $storage = $move->getStorage();
                    $tiles = $storage->take($move->getColor());
                    $player->placeTiles($tiles, $move->getRowNumber());
                    if (! $move->isFromTable()) {
                        $table->addToCenterPile($storage->takeAll());
                    }
                    $this->dispatch(new PlayerFinishTurnEvent($player, $this->round));
                }
            } else {
                $this->round = null;
                foreach ($players as $player) {
                    $player->doWallTiling();
                    $this->dispatch(new WallTiledEvent($player));
                    $this->bag->discardTiles($player->discardTiles());
                }

                foreach ($players as $player) {
                    if ($player->isGameOver()) {
                        return;
                    }
                }
            }
        }
    }

    private function createRound(PlayerCollection $players): GameRound
    {
        $table = new Table(new Marker);

        return new GameRound(
            $table,
            [
                new Factory($this->bag->getNextPlate()),
                new Factory($this->bag->getNextPlate()),
                new Factory($this->bag->getNextPlate()),
                new Factory($this->bag->getNextPlate()),
                new Factory($this->bag->getNextPlate()),
            ],
            $players
        );
    }

    private function dispatch(GameEvent $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
