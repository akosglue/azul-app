<?php

declare(strict_types=1);

namespace App\Game;

use App\Assert\Assert;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;

class Bag
{
    /**
     * @var array<string, int>
     */
    private array $tiles = [];

    /**
     * @var array<string, int>
     */
    private array $discardTiles = [];

    public static function create(): self
    {
        $bag = new self;
        foreach (Color::getAll() as $color) {
            $bag->addTiles($color, 20);
        }

        return $bag;
    }

    public function getNextPlate(): TileCollection
    {
        foreach ($this->tiles as $k => $tile) {
            Assert::notEmpty($k);
        }
        $plateTiles = new TileCollection;
        // TODO check rules - if there are 3 left in bag - game stops?
        if (array_sum($this->tiles) + array_sum($this->discardTiles) >= 4) {// @pest-mutate-ignore
            while ($plateTiles->count() !== 4) {
                $availableColors = array_keys(array_filter(
                    $this->tiles,
                    static fn ($amount) => $amount > 0
                ));
                if (! $availableColors) {
                    $this->tiles = $this->discardTiles;
                    $this->discardTiles = [];

                    continue;
                }
                shuffle($availableColors); // @pest-mutate-ignore
                $randomColor = array_pop($availableColors); // @pest-mutate-ignore
                $this->tiles[$randomColor]--;
                $plateTiles->push(new Tile($randomColor));
            }
        }

        return $plateTiles;
    }

    public function discardTiles(TileCollection $tiles): void
    {
        foreach ($tiles as $tile) {
            $color = $tile->getColor();
            if (! $color) {//marker tile wont be discarded
                continue;
            }
            $this->discardTiles[$color] = ($this->discardTiles[$color] ?? 0) + 1;
        }
    }

    public function addTiles(string $color, int $amount): self
    {
        if (! array_key_exists($color, $this->tiles)) {
            $this->tiles[$color] = 0;
        }

        $this->tiles[$color] += $amount;

        return $this;
    }

    public function getDiscardTileCountForColor(string $color): int
    {
        return $this->discardTiles[$color];
    }

    public function geTileCountForColor(string $color): int
    {
        return $this->tiles[$color];
    }
}
