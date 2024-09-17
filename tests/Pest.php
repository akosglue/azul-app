<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Board\BoardRow;
use App\Game\Table;
use App\Tile\Color;
use App\Tile\Marker;
use App\Tile\Tile;
use App\Tile\TileCollection;

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createGameTable(): \App\Game\Table
{
    return new Table(new Marker);
}
function addTile(BoardRow $row, Tile $tile): void
{
    $row->placeTiles(new TileCollection([$tile]));
}
function buildBlueTiles(int $numberOfTiles): TileCollection
{
    return new TileCollection(array_fill(1, $numberOfTiles, new Tile(Color::BLUE)));
}

function buildBlackTiles(int $numberOfTiles): TileCollection
{
    return new TileCollection(array_fill(1, $numberOfTiles, new Tile(Color::BLACK)));
}

function buildCyanTiles(int $numberOfTiles): TileCollection
{
    return new TileCollection(array_fill(1, $numberOfTiles, new Tile(Color::CYAN)));
}

function buildYellowTiles(int $numberOfTiles): TileCollection
{
    return new TileCollection(array_fill(1, $numberOfTiles, new Tile(Color::YELLOW)));
}

function buildRedTiles(int $numberOfTiles): TileCollection
{
    return new TileCollection(array_fill(1, $numberOfTiles, new Tile(Color::RED)));
}
