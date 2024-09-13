<?php

use App\Game\Bag;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

test('testGetNext_NoTiles_NoNext',function (){
    $bag = new Bag();
    $this->assertEmpty($bag->getNextPlate());
});

test('testGetNext_GameTiles_Got25Plates',function (){
    $bag = Bag::create();
    for ($j = 0; $j < 25; $j++) {
        $this->assertNotEmpty($bag->getNextPlate());
    }
    $this->assertEmpty($bag->getNextPlate());
});

test('testNextPlate_5Tiles_Get4TilesOnce',function (){
    $bag = (new Bag())->addTiles(Color::BLACK, 5);
    $this->assertCount(4, $bag->getNextPlate());
    $this->assertCount(0, $bag->getNextPlate());
});

test('testNextPlate_5TilesRefill4_Get4TilesTwice',function (){
    $bag = (new Bag())->addTiles(Color::BLACK, 5);
    $this->assertCount(4, $firstPlate = $bag->getNextPlate());
    $this->assertCount(0, $bag->getNextPlate());
    $bag->discardTiles($firstPlate);
    $this->assertCount(4, $bag->getNextPlate());
    $this->assertCount(0, $bag->getNextPlate());
});

test('testNextPlate_HasRedInTilesAndBlackInDiscard_UseDiscardedOnlyAfterTilesEmpty', function () {
    $bag = (new Bag())->addTiles($tileColor = Color::BLACK, 4);
    $bag->discardTiles(new TileCollection([
        new Tile(Color::RED),
        new Tile(Color::RED),
        new Tile(Color::RED),
        new Tile(Color::RED),
    ]));
    $plate = $bag->getNextPlate();
    foreach ($plate as $tile) {
        $this->assertEquals($tileColor, $tile->getColor());
    }
    $nextPlate = $bag->getNextPlate();
    foreach ($nextPlate as $tile) {
        $this->assertNotEquals($tileColor, $tile->getColor());
    }
});
