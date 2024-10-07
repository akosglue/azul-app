<?php

declare(strict_types=1);

namespace App\Game;

use App\Player\PlayerCollection;
use Webmozart\Assert\Assert;

class GameRound
{
    private FactoryCollection $factories;

    private Table $table;

    public function __construct(Table $table, array $factories, PlayerCollection $players)
    {
        $this->table = $table;
        $this->factories = new FactoryCollection($factories);
        Assert::countBetween($players, 2, 4);
    }

    public function canContinue(): bool
    {
        $factoriesTileCount = 0;
        foreach ($this->factories as $factory) {
            $factoriesTileCount += $factory->getTilesCount();
        }

        return $this->table->getTilesCount() > 0 || $factoriesTileCount > 0;
    }

    public function getFactories(): FactoryCollection
    {
        return $this->factories;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
