<?php

use App\Board\BoardRow;
use App\Board\Exception\BoardRowSizeExceededException;
use App\Board\Exception\BoardRowVariousColorsException;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

mutates(BoardRow::class);

test('testAdd_ExceedMinSizeCtor_GotException', function () {
    $this->expectException(InvalidArgumentException::class);
    $b = new BoardRow(0);
    $b->placeTiles(new TileCollection([new Tile(Color::YELLOW), new Tile(Color::YELLOW)]));
});

test('testAdd_ExceedMaxSizeCtor_GotException', function () {
    $this->expectException(InvalidArgumentException::class);
    $b = new BoardRow(6);
    $b->placeTiles(new TileCollection([new Tile(Color::YELLOW), new Tile(Color::YELLOW)]));
});

test('testAdd_ExceedMaxSize_GotException', function () {
    $b = new BoardRow(1);
    $this->expectException(BoardRowSizeExceededException::class);
    $b->placeTiles(new TileCollection([new Tile(Color::YELLOW), new Tile(Color::YELLOW)]));
});

test('testAddTile_ExceedMaxSize_GotException', function () {
    $b = new BoardRow(1);
    addTile($b, new Tile(Color::YELLOW));
    $this->expectException(BoardRowSizeExceededException::class);
    addTile($b, new Tile(Color::YELLOW));
});

test('testAdd_OneTileIn2MaxSize_Okay', function () {
    $b = new BoardRow(2);
    $b->placeTiles(new TileCollection([new Tile(Color::YELLOW)]));
    $this->assertEquals(1, $b->getEmptySlotsCount());
});

test('testAdd_TwoDifferentColors_GotException', function () {
    $b = new BoardRow(2);
    addTile($b, new Tile(Color::YELLOW));
    $this->expectException(BoardRowVariousColorsException::class);
    addTile($b, new Tile(Color::RED));
});

test('testAddTiles_DifferentColors_GotException', function () {
    $b = new BoardRow(5);
    $this->expectException(BoardRowVariousColorsException::class);
    $b->placeTiles(new TileCollection([new Tile(Color::YELLOW), new Tile(Color::RED)]));
});

test('testGetEmptySLots_3of5_2Empty', function () {
    $b = new BoardRow(5);
    $b->placeTiles(new TileCollection([new Tile(Color::RED), new Tile(Color::RED), new Tile(Color::RED)]));
    $this->assertEquals(2, $b->getEmptySlotsCount());
});

test('testIsMainColor_NoTiles_AnyColorIsMain', function () {
    $b = new BoardRow(1);
    foreach (Color::getAll() as $color) {
        $this->assertTrue($b->isMainColor($color));
    }
});

test('main color is empty', function () {
    $b = new BoardRow(1);
    $this->assertEquals('', $b->getMainColor());
});

test('main color is valid color', function () {
    $b = new BoardRow(1);
    addTile($b, new Tile(Color::YELLOW));
    $this->assertEquals('yellow', $b->getMainColor());
});

test('row is completed', function () {
    $b = new BoardRow(5);
    addTile($b, new Tile(Color::YELLOW));
    $this->assertFalse($b->isCompleted());
});

test('row is not completed', function () {
    $b = new BoardRow(2);
    addTile($b, new Tile(Color::YELLOW));
    addTile($b, new Tile(Color::YELLOW));
    $this->assertTrue($b->isCompleted());
});

test('get last tile from row for wall', function () {
    $b = new BoardRow(2);
    addTile($b, new Tile(Color::YELLOW));
    addTile($b, new Tile(Color::YELLOW));
    $this->assertEquals('yellow', $b->getTileForWall()->getColor());
});
