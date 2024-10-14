<?php

use App\Board\Board;
use App\Board\BoardRow;
use App\Board\BoardWall;
use App\Exceptions\BoardWallColorAlreadyFilledException;
use App\Tile\Color;
use App\Tile\Tile;

test('testIsAnyRowCompleted_EmptyWall_False', function () {
    $wall = new BoardWall;
    $this->assertFalse($wall->isAnyRowCompleted());
});

test('testIsAnyRowCompleted_AllColorsOnFirstRow_True', function () {
    $wall = new BoardWall;
    foreach (Color::getAll() as $color) {
        $row = new BoardRow(1);
        addTile($row, new Tile($color));
        $wall->fillColor($row);
    }
    $this->assertTrue($wall->isAnyRowCompleted());
});

test('testIsAnyRowCompleted_AllColorsOnAllRows_True', function () {
    $wall = new BoardWall;
    foreach (Color::getAll() as $color) {
        foreach (Board::getRowNumbers() as $rowNumber) {
            $row = new BoardRow($rowNumber);
            for ($i = 0; $i < $rowNumber; $i++) {
                addTile($row, new Tile($color));
            }
            $wall->fillColor($row);
        }
    }
    $this->assertTrue($wall->isAnyRowCompleted());

    for ($i = 0; $i < 5; $i++) {
        expect($wall->getColumn($i))->toHaveCount(5);
        foreach ($wall->getColumn($i) as $color => $tile) {
            expect($color)->toBe($tile->getColor());
        }
    }

});

test('testFillColor_SecondRowCompleted_TileTakenFromRow', function () {
    $wall = new BoardWall;
    $row = new BoardRow(2);
    addTile($row, new Tile(Color::BLUE));
    addTile($row, new Tile(Color::BLUE));

    $wall->fillColor($row);

    $this->assertCount(1, $row->getTiles());
});

test('testFillColor_FirstRowCompleted_TileTakenFromRow', function () {
    $wall = new BoardWall;
    $row = new BoardRow(1);
    addTile($row, new Tile(Color::RED));

    $wall->fillColor($row);

    $this->assertCount(0, $row->getTiles());
});

test('testPlaceTiles_OneColorTwoTimes_Exception', function () {
    $wall = new BoardWall;
    $row = new BoardRow(2);
    addTile($row, new Tile(Color::RED));
    $wall->fillColor($row);
    $this->expectException(BoardWallColorAlreadyFilledException::class);
    addTile($row, new Tile(Color::RED));
    $wall->fillColor($row);
});

test('testIsColorFilled_PlaceRed_True', function () {
    $wall = new BoardWall;
    $row = new BoardRow(1);
    $color = Color::RED;
    $this->assertFalse($wall->isColorFilled($color, Board::ROW_1));
    addTile($row, new Tile($color));
    $wall->fillColor($row);
    $this->assertTrue($wall->isColorFilled($color, Board::ROW_1));
});

test('testIsColorFilled_NothingPlaced_False', function () {
    $wall = new BoardWall;
    $row = \Mockery::mock(BoardRow::class, ['maxTiles' => 1]);
    $row->shouldReceive('getMainColor')->andReturn(Color::BLACK);
    $row->shouldReceive('getRowNumber')->andReturn(1);
    $this->assertFalse($wall->isColorFilledByRow($row));
});

test('exception for wrong high index', function () {
    $this->expectException(ErrorException::class);

    $wall = new BoardWall;
    $row = \Mockery::mock(BoardRow::class, ['maxTiles' => 1]);
    $row->shouldReceive('getMainColor')->andReturn(Color::BLACK);
    $row->shouldReceive('getRowNumber')->andReturn(1);

    $wall->isColorFilled(Color::RED, 6);
});

test('exception for wrong low index', function () {
    $this->expectException(ErrorException::class);

    $wall = new BoardWall;
    $row = \Mockery::mock(BoardRow::class, ['maxTiles' => 1]);
    $row->shouldReceive('getMainColor')->andReturn(Color::BLACK);
    $row->shouldReceive('getRowNumber')->andReturn(1);

    $wall->isColorFilled(Color::RED, 0);
});
