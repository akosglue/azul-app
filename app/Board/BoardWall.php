<?php

declare(strict_types=1);

namespace App\Board;

use App\Assert\Assert;
use App\Exceptions\BoardWallColorAlreadyFilledException;
use App\Tile\Color;
use App\Tile\Tile;

class BoardWall
{
    private const PATTERN = [
        Board::ROW_1 => [
            Color::BLUE,
            Color::YELLOW,
            Color::RED,
            Color::BLACK,
            Color::CYAN,
        ],
        Board::ROW_2 => [
            Color::CYAN,
            Color::BLUE,
            Color::YELLOW,
            Color::RED,
            Color::BLACK,
        ],
        Board::ROW_3 => [
            Color::BLACK,
            Color::CYAN,
            Color::BLUE,
            Color::YELLOW,
            Color::RED,
        ],
        Board::ROW_4 => [
            Color::RED,
            Color::BLACK,
            Color::CYAN,
            Color::BLUE,
            Color::YELLOW,
        ],
        Board::ROW_5 => [
            Color::YELLOW,
            Color::RED,
            Color::BLACK,
            Color::CYAN,
            Color::BLUE,
        ],
    ];

    /**
     * @var array<int, array<string, Tile|null>>
     */
    private array $pattern;

    public function __construct()
    {
        $this->pattern = [];
        foreach (self::PATTERN as $rowNumber => $rowColors) {
            foreach ($rowColors as $color) {
                $this->pattern[$rowNumber][$color] = null;
            }
        }
    }

    public function fillColor(BoardRow $row): void
    {
        if ($this->isColorFilledByRow($row)) {
            throw new BoardWallColorAlreadyFilledException;
        }
        $this->pattern[$row->getRowNumber()][$row->getMainColor()] = $row->getTileForWall();
    }

    public function isAnyRowCompleted(): bool
    {
        foreach ($this->pattern as $tiles) {
            if (! in_array(null, $tiles, true)) {
                return true;
            }
        }

        return false;
    }

    public function countCompletedRow(): int
    {
        $completed = 0;

        foreach ($this->pattern as $tiles) {
            if (! in_array(null, $tiles, true)) {
                $completed++;
            }
        }

        Assert::range($completed, 0, 5);

        return $completed;
    }

    public function countCompletedColumn(): int
    {
        $completed = 0;

        for ($i = 0; $i < 5; $i++) {
            $column = $this->getColumn($i);
            if (count(array_filter($column)) == 5) {
                $completed++;
            }
        }

        Assert::range($completed, 0, 5);

        return $completed;
    }

    public function countCompletedColors(): int
    {
        $completed = 0;
        foreach (Color::getAll() as $color) {
            $colors = array_column($this->pattern, $color);
            if (count(array_filter($colors)) == 5) {
                $completed++;
            }
        }

        Assert::range($completed, 0, 5);

        return $completed;
    }

    public function isColorFilled(string $color, int $rowNumber): bool
    {
        return $this->pattern[$rowNumber][$color] !== null;
    }

    public function isColorFilledByRow(BoardRow $row): bool
    {
        return $this->isColorFilled($row->getMainColor(), $row->getRowNumber());
    }

    /**
     * @return array<Tile|null>
     */
    public function getPattern(BoardRow $row): array
    {
        return $this->pattern[$row->getRowNumber()];
    }

    /**
     * @return array<mixed>
     */
    public function getColumn(int $idx): array
    {
        $colors = [];
        foreach (self::PATTERN as $rowNumber => $rowColors) {
            foreach ($rowColors as $k => $color) {
                if ($k == $idx) {
                    $colors[$color] = $this->pattern[$rowNumber][$color];
                }
            }
        }

        return $colors;
    }
}
