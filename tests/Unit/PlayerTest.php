<?php

use App\Board\Board;
use App\Board\BoardRow;
use App\Board\BoardWall;
use App\Game\Factory;
use App\Game\FactoryCollection;
use App\Player\Player;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testGetNextMove_ReturnMoveObject', function () {
    $player = new Player(new Board);
    $factory = new Factory(new TileCollection([new Tile(Color::BLUE)]));
    $move = $player->getNextMove(new FactoryCollection([$factory]), createGameTable());
    $this->assertNotNull($move);
});

test('testGetNextMove_TiledFactoryEmptyTable_TookTilesFromFactory', function () {
    $player = new Player(new Board);
    $t = createGameTable();
    $factory = new Factory(new TileCollection([new Tile(Color::BLUE)]));

    $move = $player->getNextMove(new FactoryCollection([$factory]), $t);
    $this->assertFalse($move->isFromTable());
});

test('testGetNextMove_EmptyFactoryTiledTable_TookTilesFromTable', function () {
    $player = new Player(new Board);
    $t = createGameTable();
    $t->addToCenterPile(new TileCollection([new Tile(Color::BLUE)]));

    $move = $player->getNextMove(new FactoryCollection([new Factory(new TileCollection)]),
        $t);
    $this->assertTrue($move->isFromTable());
});

test('testGetNextMove_AllWallRowsHasRedTile_TookTilesAnyway', function () {
    $player = new Player($board = new Board);
    $board->placeTiles(new TileCollection([new Tile(Color::RED)]), Board::ROW_1);
    $board->placeTiles(new TileCollection([new Tile(Color::RED)]), Board::ROW_2);
    $board->placeTiles(new TileCollection([new Tile(Color::RED)]), Board::ROW_3);
    $board->placeTiles(new TileCollection([new Tile(Color::RED)]), Board::ROW_4);
    $board->placeTiles(new TileCollection([new Tile(Color::RED)]), Board::ROW_5);
    $t = createGameTable();
    $t->addToCenterPile(new TileCollection([new Tile(Color::BLACK)]));

    $this->assertEquals(0, $board->getFloorTilesCount());
    $move = $player->getNextMove(new FactoryCollection([new Factory(new TileCollection)]), $t);
    $this->assertNotNull($move->getColor());
    $this->assertNotNull($move->getRowNumber());
});

test('testIsGameOver_AllTilesOnWallAreFilled_True', function () {
    $wall = new BoardWall;
    foreach (Color::getAll() as $color) {
        $row = new BoardRow(1);
        $row->placeTiles(new TileCollection([new Tile($color)]));
        $wall->fillColor($row);
    }
    $player = new Player($board = new Board($wall));
    $this->assertTrue($player->isGameOver());
});

test('testIsGameOver_HasEmptyColors_False', function () {
    $wall = new BoardWall;

    $count = count(Color::getAll());
    $colorsCount = 0;
    foreach (Color::getAll() as $color) {
        $colorsCount++;
        $row = new BoardRow(1);
        $row->placeTiles(new TileCollection([new Tile($color)]));
        $wall->fillColor($row);
        $player = new Player($board = new Board($wall));

        if ($colorsCount === $count) {
            $this->assertTrue($player->isGameOver());
        } else {
            $this->assertFalse($player->isGameOver());
        }
    }
    $this->assertEquals(2, $board->getScore()); //1 full first row
});
