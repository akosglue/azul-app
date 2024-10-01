<?php

declare(strict_types=1);

namespace App\Board;

use App\Assert\Assert;
use App\Tile\Tile;
use App\Tile\TileCollection;

class Board
{
    public const ROW_1 = 1;

    public const ROW_2 = 2;

    public const ROW_3 = 3;

    public const ROW_4 = 4;

    public const ROW_5 = 5;

    private BoardWall $wall;

    private TileCollection $floorLine;

    private BoardRow $row1;

    private BoardRow $row2;

    private BoardRow $row3;

    private BoardRow $row4;

    private BoardRow $row5;

    private array $rowNumberToDiscard = [];

    private int $score = 0;

    public function __construct(?BoardWall $wall = null)
    {
        $this->wall = $wall ?? new BoardWall;
        $this->row1 = new BoardRow(1);
        $this->row2 = new BoardRow(2);
        $this->row3 = new BoardRow(3);
        $this->row4 = new BoardRow(4);
        $this->row5 = new BoardRow(5);
        $this->floorLine = new TileCollection;
    }

    public static function getRowNumbers(): array
    {
        return [
            self::ROW_1,
            self::ROW_2,
            self::ROW_3,
            self::ROW_4,
            self::ROW_5,
        ];
    }

    public function placeTiles(TileCollection $tiles, $rowOrNumber): void
    {
        // TODO should check if wall color is filled by tiles color - throw exception, in Game?
        $row = $rowOrNumber instanceof BoardRow ? $rowOrNumber : $this->getRow($rowOrNumber);
        $extraCount = $tiles->count() - $row->getEmptySlotsCount();
        for ($j = 0; $j < $extraCount; $j++) {
            $this->placeOnFloor($tiles->pop());
        }
        $row->placeTiles($tiles);
    }

    public function getRow(int $rowNumber): BoardRow
    {
        Assert::range($rowNumber, 1, 5);
        switch ($rowNumber) {
            case 1:
                return $this->row1;
            case 2:
                return $this->row2;
            case 3:
                return $this->row3;
            case 4:
                return $this->row4;
            case 5:
                return $this->row5;
        }
    }

    public function getFloorTilesCount(): int
    {
        return $this->floorLine->count();
    }

    public function getRowTilesCount(int $rowNumber): int
    {
        $row = $this->getRow($rowNumber);

        return $row->getTilesCount();
    }

    /**
     * @return BoardRow[]
     */
    public function getRows(): array
    {
        return [
            $this->row1,
            $this->row2,
            $this->row3,
            $this->row4,
            $this->row5,
        ];
    }

    public function doWallTiling(): void
    {
        foreach ($this->getRows() as $row) {
            if ($row->isCompleted()) {
                if (! $this->wall->isColorFilledByRow($row)) {
                    $placedColor = $row->getMainColor();
                    $this->wall->fillColor($row);
                    $this->rowNumberToDiscard[$row->getRowNumber()] = true;
                    $this->score += $this->calculateScore($placedColor, $row);
                } else {
                    foreach ($row->getTiles() as $tile) {
                        $this->placeOnFloor($tile);
                    }
                }
            }
        }
        $this->score += $this->calculatePenalty();
    }

    private function placeOnFloor(Tile $tile): void
    {
        $this->floorLine->push($tile);
    }

    public function discardTiles(): TileCollection
    {
        $tiles = new TileCollection;
        foreach ($this->getRows() as $row) {
            if (isset($this->rowNumberToDiscard[$row->getRowNumber()])) {
                foreach ($row->getTiles()->takeAllTiles() as $tile) {
                    $tiles->addTile($tile);
                }
            }
            $this->rowNumberToDiscard[$row->getRowNumber()] = null;
        }
        foreach ($this->floorLine->takeAllTiles() as $tile) {
            $tiles->addTile($tile);
        }

        return $tiles;
    }

    public function isAnyWallRowCompleted(): bool
    {
        if ($this->wall->isAnyRowCompleted()) {
            $this->score += $this->calculateBonuses();
        }

        return $this->wall->isAnyRowCompleted();
    }

    public function getFloorTiles(): TileCollection
    {
        return $this->floorLine;
    }

    public function isWallColorFilled(string $color, int $rowNumber): bool
    {
        return $this->wall->isColorFilled($color, $rowNumber);
    }

    public function getPattern(BoardRow $row): array
    {
        return $this->wall->getPattern($row);
    }

    public function placeMarker(\App\Tile\Marker $marker): void
    {
        $this->placeOnFloor($marker);
    }

    public function getScore(): int
    {
        return $this->score;
    }

    private function calculatePenalty(): int
    {
        $score = 0;
        foreach ($this->getFloorTiles() as $c => $tile) {
            $penalty = ($c <= 1) ? -1 : ($c <= 4 ? -2 : -3);
            $score += $penalty;
        }

        return $score;
    }

    private function calculateBonuses(): int
    {
        $score = 0;
        //full row: 2
        //full column: 7
        //5 colors: 10

        $score += $this->wall->countCompletedRow() * 2;
        $score += $this->wall->countCompletedColumn() * 7;
        $score += $this->wall->countCompletedColors() * 10;

        return $score;
    }

    private function getAdjacentScoreAboveOrLeft($placedColor, $slots)
    {
        $score = 0;
        foreach ($slots as $color => $slot) {
            if ($color == $placedColor) {//found same color
                break;
            }
            if ($slot) {
                $score++;
            }
            if (! $slot) {
                $score = 0;
            }
        }

        return $score;
    }

    private function getAdjacentScoreBelowOrRight($placedColor, $slots)
    {
        $score = 0;
        $pivot = false;
        foreach ($slots as $color => $slot) {
            if ($color == $placedColor) {//found same color
                $pivot = true;

                continue;
            }
            if ($pivot && $slot == null) {
                break; //no more adjacent tile
            }
            if ($pivot) {
                $score++; //score for adjacent tile
            }
        }

        return $score;
    }

    private function calculateScore($placedColor, $row): int
    {
        $score = 1;

        $pattern = $this->getPattern($row);
        $idx = array_search($placedColor, array_keys($pattern));
        $column = $this->wall->getColumn($idx);

        $above = $this->getAdjacentScoreAboveOrLeft($placedColor, $column);
        $below = $this->getAdjacentScoreBelowOrRight($placedColor, $column);
        $left = $this->getAdjacentScoreAboveOrLeft($placedColor, $pattern);
        $right = $this->getAdjacentScoreBelowOrRight($placedColor, $pattern);

        $score += $above + $below + $left + $right;
        if (($above || $below) && ($left || $right)) {//tile in intersection
            $score += 1;
        }

        return $score;
    }
}
