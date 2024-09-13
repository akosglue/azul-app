<?php

namespace App\Player\Strategy;

use App\Board\Board;
use App\Game\FactoryCollection;
use App\Game\Table;
use App\Player\Move;

abstract class AbstractStrategy
{
	protected Board $board;

	public function __construct(Board $board)
	{
		$this->board = $board;
	}

	abstract public function getNextMove(FactoryCollection $factories, Table $table): ?Move;
}