<?php

use App\Board\Board;
use App\Events\RoundCreatedEvent;
use App\Game\Bag;
use App\Game\Game;
use App\Player\Player;
use App\Player\PlayerCollection;
use Illuminate\Support\Facades\Event;

test('testPlay_2Players_gameHasEnding',function (){
    $players = new PlayerCollection([new Player(new Board(), 'Ivan'), new Player(new Board(), 'Petr')]);
    $game = new Game(Bag::create());
    $game->play($players);
})->throwsNoExceptions();