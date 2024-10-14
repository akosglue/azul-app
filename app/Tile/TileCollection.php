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
     * @param  array<Tile>|Tile|TileCollection  $tiles
     */
    public function __construct(array|Tile|TileCollection $tiles = [])
    {
        if ($tiles instanceof Tile) {
            $tiles = [$tiles];
        }
        foreach ($tiles as $tile) {
            $this->addTile($tile);
        }
    }

    public function addTile(Tile $tile): void
    {
        $this->push($tile);
    }

    public function takeAllTiles(): TileCollection
    {
        $tiles = new TileCollection;
        while ($this->count() > 0) {
            $tiles->push($this->pop());
        }

        return $tiles;
    }
}
