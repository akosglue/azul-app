<?php

namespace App\Player\Strategy;

use App\Board\Board;
use App\Game\FactoryCollection;
use App\Game\Table;
use App\Player\Move;
use App\Tile\Color;

class FastestGameStrategy extends AbstractStrategy
{
    public function getNextMove(FactoryCollection $factories, Table $table): ?Move
    {
        foreach ($this->board->getRows() as $row) {
            if ($row->getTilesCount() === 0) {
                // row is empty, get any color that is not on the wall
                foreach ($this->getColors() as $color) {
                    if ($this->board->isWallColorFilled($color, $row->getRowNumber())) {
                        continue;
                    }
                    // look for this color, take it from anywhere
                    $move = $this->getMoveByColorAndRow(
                        $factories,
                        $table,
                        $color,
                        $row->getRowNumber()
                    );
                    if ($move) {
                        return $move;
                    }
                }
            } else {
                if ($row->getEmptySlotsCount() > 0) {
                    // row already got tiles, look for the same color anywhere
                    $color = $row->getMainColor();
                    $move = $this->getMoveByColorAndRow(
                        $factories,
                        $table,
                        $color,
                        $row->getRowNumber()
                    );
                    if ($move) {
                        return $move;
                    }
                }
            }
        }

        // get any color to first row/on floor
        foreach ($this->getColors() as $color) {
            $move = $this->getMoveByColorAndRow(
                $factories,
                $table,
                $color,
                Board::ROW_1
            );
            if ($move) {
                return $move;
            }
        }

        return null;
    }

    /**
     * @return array<string>
     */
    private function getColors(): array
    {
        $colors = Color::getAll();
        shuffle($colors);

        return $colors;
    }

    private function getMoveByColorAndRow(
        FactoryCollection $factories,
        Table $table,
        string $color,
        int $getRowNumber
    ): ?Move {
        foreach ($factories as $factory) {
            if ($factory->getTilesCount($color) === 0) {
                continue;
            }

            return new Move($color, $getRowNumber, $factory);
        }
        if ($table->getTilesCount($color)) {
            return new Move($color, $getRowNumber, $table);
        }

        return null;
    }
}
