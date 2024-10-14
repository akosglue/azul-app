<?php

use App\Game\Factory;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testTakeRed_3Red1Black_1TileLeft', function () {
    $factory = new Factory(
        new TileCollection([
            new Tile(Color::RED),
            new Tile(Color::RED),
            new Tile(Color::RED),
            new Tile(Color::BLACK),
        ])
    );
    $this->assertEquals(3, $factory->getTilesCount(Color::RED));
    $this->assertEquals(1, $factory->getTilesCount(Color::BLACK));
    $tiles = $factory->take(Color::RED);
    $this->assertCount(3, $tiles);
    $this->assertEquals(0, $factory->getTilesCount(Color::RED));
    $this->assertEquals(1, $factory->getTilesCount(Color::BLACK));
});

test('testTakeAll_3Red1Black_NoTilesLeft', function () {
    $factory = new Factory(
        new TileCollection([
            new Tile(Color::RED),
            new Tile(Color::RED),
            new Tile(Color::RED),
            new Tile(Color::BLACK),
        ])
    );
    $this->assertEquals(3, $factory->getTilesCount(Color::RED));
    $this->assertEquals(1, $factory->getTilesCount(Color::BLACK));
    $tiles = $factory->takeAll();
    $this->assertCount(4, $tiles);
    $this->assertEquals(0, $factory->getTilesCount(Color::RED));
    $this->assertEquals(0, $factory->getTilesCount(Color::BLACK));
    $this->assertEquals(0, $factory->getTilesCount());
    $this->assertCount(0, $factory->getTiles());
});

test('testTake_NoExistedColor_Exception', function () {
    $factory = new Factory(
        new TileCollection(array_fill(0, 4, new Tile(Color::BLACK)))
    );
    $this->expectExceptionMessageMatches('#at least 1#');
    $factory->take(Color::CYAN);
});
