<?php

use App\Exceptions\MarkerAlreadyTakenException;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testCountTotal_2DifferentColors_Total2', function () {
    $table = createGameTable();
    $table->addToCenterPile(new TileCollection([
        new Tile(Color::RED),
        new Tile(Color::CYAN),
    ]));
    $this->assertEquals(2, $table->getTilesCount());
});

test('testCountTotal_2SameColors_Total2', function () {
    $table = createGameTable();
    $table->addToCenterPile(new TileCollection([
        new Tile(Color::RED),
        new Tile(Color::RED),
    ]));
    $this->assertEquals(2, $table->getTilesCount());
});

test('testCountTotal_Empty_Total0', function () {
    $table = createGameTable();
    $this->assertEquals(0, $table->getTilesCount());
});

test('testCountByColor', function () {
    $table = createGameTable();
    $table->addToCenterPile(new TileCollection([
        new Tile(Color::RED),
        new Tile(Color::RED),
        new Tile(Color::CYAN),
        new Tile(Color::RED),
        new Tile(Color::CYAN),
        new Tile(Color::BLUE),
    ]));
    $this->assertEquals(3, $table->getTilesCount(Color::RED));
    $this->assertEquals(2, $table->getTilesCount(Color::CYAN));
    $this->assertEquals(1, $table->getTilesCount(Color::BLUE));
    $this->assertEquals(0, $table->getTilesCount(Color::YELLOW));
});

test('testTakeMarker_HasMarker_NoMarkerAfter', function () {
    $table = createGameTable();
    $this->assertTrue($table->hasMarker());
    $marker = $table->takeMarker();
    $this->assertNotNull($marker);
    $this->assertFalse($table->hasMarker());
});

test('testTakeMarker_Twice_GotException', function () {
    $table = createGameTable();
    $table->takeMarker();
    $this->expectException(MarkerAlreadyTakenException::class);
    $table->takeMarker();
});

test('testTake_HasMarker_MarkerLeft', function () {
    $table = createGameTable();
    $color = Color::RED;
    $table->addToCenterPile(new TileCollection([
        new Tile($color),
    ]));
    $this->assertTrue($table->hasMarker());
    $tiles = $table->take($color);
    $this->assertCount(1, $tiles);
    $this->assertTrue($table->hasMarker());
    $this->assertEquals(0, $table->getTilesCount());
});
