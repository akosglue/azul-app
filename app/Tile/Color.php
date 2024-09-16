<?php

declare(strict_types=1);

namespace App\Tile;

class Color
{
    public const BLACK = 'black'; // TODO waiting for enums in php 8.1

    public const BLUE = 'blue';

    public const CYAN = 'cyan';

    public const RED = 'red';

    public const YELLOW = 'yellow';

    public static function getAll(): array
    {
        return [
            self::BLACK,
            self::BLUE,
            self::CYAN,
            self::RED,
            self::YELLOW,
        ];
    }
}
