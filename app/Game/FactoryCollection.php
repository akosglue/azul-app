<?php

declare(strict_types=1);

namespace App\Game;

/**
 * @method Factory pop()
 * @method Factory current()
 */

/** @extends \ArrayIterator<int|string, Factory> */
class FactoryCollection extends \ArrayIterator {}
