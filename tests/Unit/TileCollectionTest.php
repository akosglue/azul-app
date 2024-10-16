<?php

use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

mutates(TileCollection::class);

test('can be created with single tile', function () {
    $t = TileCollection::createWithTile(new Tile(Color::BLUE));
    $this->assertCount(1, $t->takeAllTiles());
});

test('can be created with array of tiles', function () {
    $t = TileCollection::createWithTiles([
        new Tile(Color::BLUE),
        new Tile(Color::BLUE),
    ]);
    $this->assertCount(2, $t->takeAllTiles());
});
