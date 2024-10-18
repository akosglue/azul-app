<?php

use App\Board\Board;
use App\Game\Factory;
use App\Game\GameRound;
use App\Player\Player;
use App\Player\PlayerCollection;
use App\Tile\Color;
use App\Tile\Tile;
use App\Tile\TileCollection;
use Webmozart\Assert\InvalidArgumentException;

mutates(GameRound::class);

test('testKeepPlaying_EmptyFactoriesAndTable_False', function () {
    $table = createGameTable();
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
    ]);
    $table->addToCenterPile(TileCollection::createWithTiles([new Tile(Color::YELLOW)]));
    $round = new GameRound($table,
        [
            $factory1 = new Factory(
                TileCollection::createWithTiles([
                    new Tile(Color::YELLOW),
                    new Tile(Color::YELLOW),
                    new Tile(Color::YELLOW),
                    new Tile(Color::YELLOW),
                ])
            ),
            $factory2 = new Factory(
                TileCollection::createWithTiles([
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                ])
            ),
            $factory3 = new Factory(
                TileCollection::createWithTiles([
                    new Tile(Color::RED),
                    new Tile(Color::RED),
                    new Tile(Color::RED),
                    new Tile(Color::RED),
                ])
            ),
            $factory4 = new Factory(
                TileCollection::createWithTiles([
                    new Tile(Color::BLUE),
                    new Tile(Color::BLUE),
                    new Tile(Color::BLUE),
                    new Tile(Color::BLUE),
                ])
            ),
            $factory5 = new Factory(
                TileCollection::createWithTiles([
                    new Tile(Color::BLACK),
                    new Tile(Color::BLACK),
                    new Tile(Color::BLACK),
                    new Tile(Color::BLACK),
                ])
            ),
        ],
        $players
    );
    $this->assertTrue($round->canContinue());

    $table->take(Color::YELLOW);
    $this->assertTrue($round->canContinue());

    $factory1->take(Color::YELLOW);
    $this->assertTrue($round->canContinue());

    $factory2->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $factory3->take(Color::RED);
    $this->assertTrue($round->canContinue());

    $factory4->take(Color::BLUE);
    $this->assertTrue($round->canContinue());

    $factory5->take(Color::BLACK);

    $this->assertFalse($round->canContinue());
});

test('multiple factory contents', function ($f1, $f2, $f3, $f4, $f5, $f6, $f7) {
    $t = createGameTable();
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
        new Player(new Board, 'Jonas'),
    ]);
    $round = new GameRound($t,
        [$f1, $f2, $f3, $f4, $f5, $f6, $f7],
        $players
    );
    $this->assertTrue($round->canContinue());

    $f1->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $f2->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $f3->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $f4->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $f5->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $f6->take(Color::CYAN);
    $this->assertTrue($round->canContinue());

    $f7->take(Color::CYAN);

    $this->assertFalse($round->canContinue());
})->with([
    'five tiles' => [
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
    ],
    'four tiles' => [
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
    ],
    'three tiles' => [
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
    ],
    'two tiles' => [
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
                new Tile(Color::CYAN),
            ])
        ),
    ],
    'one tile' => [
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
        new Factory(
            TileCollection::createWithTiles([
                new Tile(Color::CYAN),
            ])
        ),
    ],
]);

test('cannot take from empty factory', function () {
    $this->expectException(InvalidArgumentException::class);
    $t = createGameTable();
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
        new Player(new Board, 'Bert'),
        new Player(new Board, 'Jan'),
    ]);
    $round = new GameRound($t,
        [
            $f = new Factory(
                TileCollection::createEmpty()
            ),
        ],
        $players
    );
    $this->assertFalse($round->canContinue());
    $f->take(Color::CYAN);
    $this->assertFalse($round->canContinue());
});

test('no exception with correct num of players', function ($players) {
    $t = createGameTable();
    $factories = [
        new Factory(
            TileCollection::createEmpty()
        ),
        new Factory(
            TileCollection::createEmpty()
        ),
        new Factory(
            TileCollection::createEmpty()
        ),
        new Factory(
            TileCollection::createEmpty()
        ),
        new Factory(
            TileCollection::createEmpty()
        ),
    ];
    if (count($players) == 3) {
        $factories[] = new Factory(
            TileCollection::createEmpty()
        );
        $factories[] = new Factory(
            TileCollection::createEmpty()
        );
    }
    if (count($players) == 4) {
        $factories[] = new Factory(
            TileCollection::createEmpty()
        );
        $factories[] = new Factory(
            TileCollection::createEmpty()
        );
        $factories[] = new Factory(
            TileCollection::createEmpty()
        );
        $factories[] = new Factory(
            TileCollection::createEmpty()
        );
    }
    $round = new GameRound($t,
        $factories,
        $players
    );
})->with([
    '2 players' => new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
    ]),
    '3 players' => new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
        new Player(new Board, 'Ivan3'),
    ]),
    '4 players' => new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
        new Player(new Board, 'Ivan3'),
        new Player(new Board, 'Ivan4'),
    ]),
])->throwsNoExceptions();

test('exception with incorrect num of players', function ($players) {
    $this->expectException(InvalidArgumentException::class);
    $t = createGameTable();
    $round = new GameRound($t,
        [
            $f = new Factory(
                TileCollection::createEmpty()
            ),
        ],
        $players
    );
})->with([
    'no players' => new PlayerCollection([

    ]),
    'one player' => new PlayerCollection([
        new Player(new Board, 'Ivan'),
    ]),
    'five players' => new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
        new Player(new Board, 'Ivan3'),
        new Player(new Board, 'Ivan4'),
        new Player(new Board, 'Ivan5'),
    ]),
]);
