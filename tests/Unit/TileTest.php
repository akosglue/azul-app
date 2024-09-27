<?php

use App\Tile\Color;
use App\Tile\Tile;

mutates(Color::class, Tile::class);

test('total number of all colors', function () {
    $this->assertCount(5, Color::getAll());
});

test('testISameColor_EveryColor_ColorIsRight', function () {
    foreach (Color::getAll() as $colorForTile) {
        $tile = new Tile($colorForTile);
        foreach (Color::getAll() as $color) {
            if ($color === $colorForTile) {
                $this->assertTrue($tile->isSameColor($color));
            } else {
                $this->assertFalse($tile->isSameColor($color));
            }
        }
    }
});
