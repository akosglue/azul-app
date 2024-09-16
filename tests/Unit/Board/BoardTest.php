<?php

use App\Board\Board;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testPlaceTests_2inRow1_1isOnFloor', function () {
    $b = new Board;

    $this->assertEquals(0, $b->getRowTilesCount(Board::ROW_1));
    $this->assertEquals(0, $b->getFloorTilesCount());

    $b->placeTiles(new TileCollection([new Tile(Color::RED), new Tile(Color::RED)]), Board::ROW_1);
    $this->assertEquals(1, $b->getFloorTilesCount());
    $this->assertEquals(1, $b->getRowTilesCount(Board::ROW_1));
});

test('testPlaceTests_1inRow2_NothingOnFloor', function () {
    $b = new Board;
    $this->assertEquals(0, $b->getRowTilesCount(Board::ROW_2));
    $this->assertEquals(0, $b->getFloorTilesCount());
    $b->placeTiles(new TileCollection([new Tile(Color::RED)]), Board::ROW_2);
    $this->assertEquals(0, $b->getFloorTilesCount());
    $this->assertEquals(1, $b->getRowTilesCount(Board::ROW_2));
});

test('testPlaceTiles_4TilesOn2Row_2OnFloor', function () {
    $b = new Board;
    $tiles = [new Tile(Color::RED), new Tile(Color::RED), new Tile(Color::RED), new Tile(Color::RED)];
    $b->placeTiles(new TileCollection($tiles), Board::ROW_2);
    $this->assertEquals(2, $b->getFloorTilesCount());
    $this->assertEquals(2, $b->getRowTilesCount(Board::ROW_2));
});

test('testDiscardTiles_TileOnFloor_FloorEmpty', function () {
    $board = new Board;
    $color = Color::RED;
    $row = Board::ROW_1;
    $board->placeTiles(new TileCollection(new Tile($color)), $row);
    $board->placeTiles(new TileCollection(new Tile($color)), $row); // on floor

    $this->assertEquals(1, $board->getFloorTilesCount());
    $this->assertEquals(1, $board->getRowTilesCount($row));
    $this->assertFalse($board->isWallColorFilled($color, $row));

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertEquals(1, $tiles->count());
    $this->assertEquals(0, $board->getFloorTilesCount());
    $this->assertEquals(0, $board->getRowTilesCount($row));
    $this->assertTrue($board->isWallColorFilled($color, $row));
    $this->assertEquals(0, $board->getScore()); // 1 lone tile on pattern, 1 lone tile on floor = 0 score
});

test('testDiscardTiles_RowsFull_AllTilesDiscarded', function () {
    $board = new Board;
    $board->placeTiles(buildTiles(1), Board::ROW_1);
    $board->placeTiles(buildTiles(2), Board::ROW_2);
    $board->placeTiles(buildTiles(3), Board::ROW_3);
    $board->placeTiles(buildTiles(4), Board::ROW_4);
    $board->placeTiles(buildTiles(5), Board::ROW_5);

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(5, $board->getScore()); //each row was full
});

test('testDiscardTiles_EmptyRows_NothingDiscarded', function () {
    $board = new Board;
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(0, $tiles);
    $this->assertEquals(0, $board->getScore());
});

test('testDiscardTiles_2Row1Tile_NothingDiscarded', function () {
    $rowNumber = Board::ROW_2;
    $board = new Board;
    $board->placeTiles(buildTiles(1), $rowNumber);

    $this->assertEquals(1, $board->getRowTilesCount($rowNumber));

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(0, $tiles);
    $this->assertEquals(1, $board->getRowTilesCount($rowNumber));
    $this->assertEquals(0, $board->getScore()); // no full row
});

test('testDiscardTiles_2Row2Tile_1TileDiscarded1OnWall', function () {
    $rowNumber = Board::ROW_2;
    $board = new Board;
    $board->placeTiles(buildTiles(2), $rowNumber);

    $this->assertEquals(2, $board->getRowTilesCount($rowNumber));
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(1, $tiles);
    $this->assertEquals(0, $board->getRowTilesCount($rowNumber));
    $this->assertEquals(1, $board->getScore()); // one full row
});
