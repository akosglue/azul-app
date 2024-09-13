<?php

use App\Game\Factory;
use App\Game\GameRound;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testKeepPlaying_EmptyFactoriesAndTable_False',function (){
    $t = createGameTable();
    $round = new GameRound($t,
        [
            $f = new Factory(
                new TileCollection([
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                ])
            ),
        ]
    );
    $this->assertTrue($round->canContinue());
    $f->take(Color::CYAN);
    $this->assertFalse($round->canContinue());
});