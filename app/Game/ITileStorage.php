<?php

declare(strict_types=1);

namespace App\Game;

use App\Tile\TileCollection;

interface ITileStorage
{
	public function take(string $color): TileCollection;

	public function getTilesCount(?string $color = null): int;
}