<?php

declare(strict_types=1);

namespace App\Tile;

/**
 * @method Tile current()
 * @method Tile bottom()
 * @method Tile top()
 */

/** @extends \SplStack<Tile> */
class TileCollection extends \SplStack
{
    /**
     * @param  array<Tile>  $tiles
     */
    private function __construct(array $tiles)
    {
        foreach ($tiles as $tile) {
            $this->addTile($tile);
        }
    }

    public static function createEmpty(): TileCollection
    {
        return new TileCollection([]);
    }

    public static function createWithTile(Tile $tile): TileCollection
    {
        return new TileCollection([$tile]);
    }

    /**
     * @param  array<Tile>  $tiles
     */
    public static function createWithTiles(array $tiles): TileCollection
    {
        return new TileCollection($tiles);
    }

    public function addTile(Tile $tile): void
    {
        $this->push($tile);
    }

    public function takeAllTiles(): TileCollection
    {
        $tiles = TileCollection::createEmpty();
        while ($this->count() > 0) {
            $tiles->push($this->pop());
        }

        return $tiles;
    }
}
