<?php

use App\Board\Board;
use App\Game\Bag;
use App\Game\Game;
use App\Player\Player;
use App\Player\PlayerCollection;
use Illuminate\Events\Dispatcher;

test('testPlay_2Players_gameHasEnding', function () {
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
    ]);
    $game = new Game(Bag::create(), $this->createMock(Dispatcher::class));
    $game->play($players);
})->throwsNoExceptions();

test('testPlay_3Players_gameHasEnding', function () {
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
        new Player(new Board, 'Jan'),
    ]);
    $game = new Game(Bag::create(), $this->createMock(Dispatcher::class));
    $game->play($players);
})->throwsNoExceptions();

test('testPlay_4Players_gameHasEnding', function () {
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
        new Player(new Board, 'Jan'),
        new Player(new Board, 'Marc'),
    ]);
    $game = new Game(Bag::create(), $this->createMock(Dispatcher::class));
    $game->play($players);
})->throwsNoExceptions();
