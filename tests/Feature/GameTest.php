<?php

use App\Board\Board;
use App\Game\Bag;
use App\Game\Game;
use App\Player\Player;
use App\Player\PlayerCollection;

test('testPlay_2Players_gameHasEnding', function () {
    $players = new PlayerCollection([new Player(new Board, 'Ivan'), new Player(new Board, 'Petr')]);
    $game = new Game(Bag::create(), $this->createMock(\Illuminate\Events\Dispatcher::class));
    $game->play($players);
})->throwsNoExceptions();
