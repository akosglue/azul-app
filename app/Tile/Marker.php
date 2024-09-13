<?php

declare(strict_types=1);

namespace App\Tile;

class Marker extends Tile
{
	public function __construct()
	{
		// TODO marker should be independent, no inheritance
		parent::__construct('');
	}
}