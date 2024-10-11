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

    private GameRound $round;

    private bool $shouldCreateRound = true;

    private Dispatcher $dispatcher;

    public function __construct(Bag $bag, Dispatcher $dispatcher)
    {
        $this->bag = $bag;
        $this->dispatcher = $dispatcher;
    }

    public function play(PlayerCollection $players): void
    {
        while (true) {
            if ($this->shouldCreateRound) {
                $this->round = $this->createRound($players);
                $this->dispatch(new RoundCreatedEvent($this->round));
                $this->shouldCreateRound = false;
            }
            if ($this->round->canContinue()) {
                $this->shouldCreateRound = false;
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
                $this->shouldCreateRound = true;
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

        $factories = [
            new Factory($this->bag->getNextPlate()),
            new Factory($this->bag->getNextPlate()),
            new Factory($this->bag->getNextPlate()),
            new Factory($this->bag->getNextPlate()),
            new Factory($this->bag->getNextPlate()),
        ];

        if (count($players) == 3) {
            $factories[] = new Factory($this->bag->getNextPlate());
            $factories[] = new Factory($this->bag->getNextPlate());
        }

        if (count($players) == 4) {
            $factories[] = new Factory($this->bag->getNextPlate());
            $factories[] = new Factory($this->bag->getNextPlate());
            $factories[] = new Factory($this->bag->getNextPlate());
            $factories[] = new Factory($this->bag->getNextPlate());
        }

        return new GameRound(
            $table,
            $factories,
            $players
        );
    }

    private function dispatch(GameEvent $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
