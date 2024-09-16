<?php

use App\Tile\Color;
use App\Tile\Tile;

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
