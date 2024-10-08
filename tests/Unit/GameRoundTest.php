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
    $t = createGameTable();
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
    ]);
    $t->addToCenterPile(new TileCollection([new Tile(Color::YELLOW)]));
    $round = new GameRound($t,
        [
            $f = new Factory(
                new TileCollection([
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                    new Tile(Color::CYAN),
                ])
            ),
        ],
        $players
    );
    $this->assertTrue($round->canContinue());
    $f->take(Color::CYAN);
    $this->assertTrue($round->canContinue());
    $t->take(Color::YELLOW);
    $this->assertFalse($round->canContinue());
});

test('multiple factory contents', function ($f) {
    $t = createGameTable();
    $players = new PlayerCollection([
        new Player(new Board, 'Ivan'),
        new Player(new Board, 'Petr'),
        new Player(new Board, 'Jonas'),
    ]);
    $round = new GameRound($t,
        [
            $f,
        ],
        $players
    );
    $this->assertTrue($round->canContinue());
    $f->take(Color::CYAN);
    $this->assertFalse($round->canContinue());
})->with([
    new Factory(
        new TileCollection([
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
        ])
    ),
    new Factory(
        new TileCollection([
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
        ])
    ),
    new Factory(
        new TileCollection([
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
        ])
    ),
    new Factory(
        new TileCollection([
            new Tile(Color::CYAN),
            new Tile(Color::CYAN),
        ])
    ),
    new Factory(
        new TileCollection([
            new Tile(Color::CYAN),
        ])
    ),
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
                new TileCollection([

                ])
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
    $round = new GameRound($t,
        [
            $f = new Factory(
                new TileCollection([

                ])
            ),
        ],
        $players
    );
})->with([
    new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
    ]),
    new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
        new Player(new Board, 'Ivan3'),
    ]),
    new PlayerCollection([
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
                new TileCollection([

                ])
            ),
        ],
        $players
    );
})->with([
    new PlayerCollection([

    ]),
    new PlayerCollection([
        new Player(new Board, 'Ivan'),
    ]),
    new PlayerCollection([
        new Player(new Board, 'Ivan1'),
        new Player(new Board, 'Ivan2'),
        new Player(new Board, 'Ivan3'),
        new Player(new Board, 'Ivan4'),
        new Player(new Board, 'Ivan5'),
    ]),
]);
