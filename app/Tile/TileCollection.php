<?php

declare(strict_types=1);

namespace App\Tile;

/**
 * @implements \IteratorAggregate<Tile>
 */
class TileCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var \SplStack<Tile>
     */
    private \SplStack $tiles;

    /**
     * @param  array<Tile>  $tiles
     */
    private function __construct(array $tiles)
    {
        $this->tiles = new \SplStack;
        foreach ($tiles as $tile) {
            $this->addTile($tile);
        }
    }

    public function getIterator(): \Traversable
    {
        return $this->tiles;
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
        $this->tiles->push($tile);
    }

    public function takeAllTiles(): TileCollection
    {
        $tiles = TileCollection::createEmpty();
        while ($this->count() > 0) {
            $tiles->push($this->pop());
        }

        return $tiles;
    }

    public function count(): int
    {
        return $this->tiles->count();
    }

    public function pop(): Tile
    {
        return $this->tiles->pop();
    }

    public function push(Tile $tile): void
    {
        $this->tiles->push($tile);
    }

    public function bottom(): Tile
    {
        return $this->tiles->bottom();
    }
}
