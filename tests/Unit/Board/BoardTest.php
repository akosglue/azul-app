<?php

use App\Board\Board;
use App\Player\Player;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testPlaceTests_2inRow1_1isOnFloor', function () {
    $b = new Board;

    $this->assertEquals(0, $b->getRowTilesCount(Board::ROW_1));
    $this->assertEquals(0, $b->getFloorTilesCount());

    $b->placeTiles(TileCollection::createWithTiles([new Tile(Color::RED), new Tile(Color::RED)]), Board::ROW_1);
    $this->assertEquals(1, $b->getFloorTilesCount());
    $this->assertEquals(1, $b->getRowTilesCount(Board::ROW_1));
});

test('testPlaceTests_1inRow2_NothingOnFloor', function () {
    $b = new Board;
    $this->assertEquals(0, $b->getRowTilesCount(Board::ROW_2));
    $this->assertEquals(0, $b->getFloorTilesCount());
    $b->placeTiles(TileCollection::createWithTiles([new Tile(Color::RED)]), Board::ROW_2);
    $this->assertEquals(0, $b->getFloorTilesCount());
    $this->assertEquals(1, $b->getRowTilesCount(Board::ROW_2));
});

test('testPlaceTiles_4TilesOn2Row_2OnFloor', function () {
    $b = new Board;
    $tiles = [new Tile(Color::RED), new Tile(Color::RED), new Tile(Color::RED), new Tile(Color::RED)];
    $b->placeTiles(TileCollection::createWithTiles($tiles), Board::ROW_2);
    $this->assertEquals(2, $b->getFloorTilesCount());
    $this->assertEquals(2, $b->getRowTilesCount(Board::ROW_2));
});

test('testDiscardTiles_TileOnFloor_FloorEmpty', function () {
    $board = new Board;
    $color = Color::RED;
    $row = Board::ROW_1;
    $board->placeTiles(TileCollection::createWithTile(new Tile($color)), $row);
    $board->placeTiles(TileCollection::createWithTile(new Tile($color)), $row); // on floor

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
    $board->placeTiles(buildBlueTiles(1), Board::ROW_1);
    $board->placeTiles(buildBlueTiles(2), Board::ROW_2);
    $board->placeTiles(buildBlueTiles(3), Board::ROW_3);
    $board->placeTiles(buildBlueTiles(4), Board::ROW_4);
    $board->placeTiles(buildBlueTiles(5), Board::ROW_5);

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(5, $board->getScore()); //each row was full

    //add second color to 2nd row
    $board->placeTiles(buildCyanTiles(2), Board::ROW_2);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(1, $tiles);
    $this->assertEquals(9, $board->getScore());

    //add more color to 1st row
    $board->placeTiles(buildYellowTiles(1), Board::ROW_1);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(0, $tiles);
    $this->assertEquals(13, $board->getScore());

    //add one color to 3rd row
    $board->placeTiles(buildBlackTiles(3), Board::ROW_3);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(2, $tiles);
    $this->assertEquals(16, $board->getScore());
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
    $board->placeTiles(buildBlueTiles(1), $rowNumber);

    $this->assertEquals(1, $board->getRowTilesCount($rowNumber));

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(0, $tiles);
    $this->assertEquals(1, $board->getRowTilesCount($rowNumber));

    $player = new Player($board);
    $this->assertFalse($player->isGameOver());

    $this->assertEquals(0, $board->getScore()); // no full row
});

test('testDiscardTiles_2Row2Tile_1TileDiscarded1OnWall', function () {
    $rowNumber = Board::ROW_2;
    $board = new Board;
    $board->placeTiles(buildBlueTiles(2), $rowNumber);

    $this->assertEquals(2, $board->getRowTilesCount($rowNumber));
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(1, $tiles);
    $this->assertEquals(0, $board->getRowTilesCount($rowNumber));

    $player = new Player($board);
    $this->assertFalse($player->isGameOver());

    $this->assertEquals(1, $board->getScore()); // one full row
});

test('testCorrect_Scoring_After_Multiple_Tiling', function () {
    $board = new Board;
    $board->placeTiles(buildBlueTiles(1), Board::ROW_1);
    $board->placeTiles(buildBlueTiles(2), Board::ROW_2);
    $board->placeTiles(buildBlueTiles(3), Board::ROW_3);
    $board->placeTiles(buildBlueTiles(4), Board::ROW_4);
    $board->placeTiles(buildBlueTiles(5), Board::ROW_5);

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(5, $board->getScore()); //each row was full

    //second color
    $board->placeTiles(buildRedTiles(1), Board::ROW_1);
    $board->placeTiles(buildRedTiles(2), Board::ROW_2);
    $board->placeTiles(buildRedTiles(3), Board::ROW_3);
    $board->placeTiles(buildRedTiles(4), Board::ROW_4);
    $board->placeTiles(buildRedTiles(5), Board::ROW_5);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(10, $board->getScore()); //each row was full, nowhere multiscores

    //add third color
    $board->placeTiles(buildCyanTiles(1), Board::ROW_1);
    $board->placeTiles(buildCyanTiles(2), Board::ROW_2);
    $board->placeTiles(buildCyanTiles(3), Board::ROW_3);
    $board->placeTiles(buildCyanTiles(4), Board::ROW_4);
    $board->placeTiles(buildCyanTiles(5), Board::ROW_5);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(27, $board->getScore());

    //add 4th color
    $board->placeTiles(buildYellowTiles(1), Board::ROW_1);
    $board->placeTiles(buildYellowTiles(2), Board::ROW_2);
    $board->placeTiles(buildYellowTiles(3), Board::ROW_3);
    $board->placeTiles(buildYellowTiles(4), Board::ROW_4);
    $board->placeTiles(buildYellowTiles(5), Board::ROW_5);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(59, $board->getScore());

    //add last color
    $board->placeTiles(buildBlackTiles(1), Board::ROW_1);
    $board->placeTiles(buildBlackTiles(2), Board::ROW_2);
    $board->placeTiles(buildBlackTiles(3), Board::ROW_3);
    $board->placeTiles(buildBlackTiles(4), Board::ROW_4);
    $board->placeTiles(buildBlackTiles(5), Board::ROW_5);
    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertCount(10, $tiles);
    $this->assertEquals(109, $board->getScore()); //full table

    $player = new Player($board);
    $this->assertTrue($player->isGameOver());
    $this->assertEquals(109 + (5 * 2) + (5 * 7) + (5 * 10), $board->getScore()); //with bonuses
});

test('tiles are discarded if a color is already tiled', function () {
    $board = new Board;
    $color = Color::RED;
    $row = Board::ROW_1;
    $board->placeTiles(TileCollection::createWithTile(new Tile($color)), $row);

    $this->assertEquals(1, $board->getRowTilesCount($row));
    $this->assertFalse($board->isWallColorFilled($color, $row));

    $board->doWallTiling();
    $tiles = $board->discardTiles();
    $this->assertEquals(0, $tiles->count());

    $board->placeTiles(TileCollection::createWithTile(new Tile($color)), $row);
    $this->assertTrue($board->isWallColorFilled($color, $row));
    $board->doWallTiling();
    $this->assertEquals(1, $board->getFloorTilesCount());
});
