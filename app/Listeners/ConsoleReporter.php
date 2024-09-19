<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Board\Board;
use App\Events\PlayerFinishTurnEvent;
use App\Events\RoundCreatedEvent;
use App\Events\WallTiledEvent;
use App\Player\Player;
use App\Player\PlayerCollection;
use App\Tile\Color;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleReporter
{
    private const EMPTY_SLOT_SIGNS = [
        Color::BLACK => '🖤',
        Color::BLUE => '💙',
        Color::YELLOW => '💛',
        Color::CYAN => '💚',
        Color::RED => '❤️',
    ];

    private const SECONDS_PAUSE_BETWEEN_MOVES = 100000;

    private $output;

    private \App\Game\GameRound $round;

    /** @var Player[] */
    private array $players = [];

    public function __construct(PlayerCollection $players, OutputInterface $output)
    {
        $this->output = $output;
        foreach ($players as $player) {
            $this->setPlayer($player);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            RoundCreatedEvent::class,
            [$this, 'onRoundCreated']
        );

        $events->listen(
            PlayerFinishTurnEvent::class,
            [$this, 'onPlayerFinishTurn']
        );

        $events->listen(
            WallTiledEvent::class,
            [$this, 'onWallTiled']
        );
    }

    public function onRoundCreated(RoundCreatedEvent $event): void
    {
        $this->round = $event->getRound();
        $this->drawReport();
    }

    public function onPlayerFinishTurn(PlayerFinishTurnEvent $event): void
    {
        $this->round = $event->getRound();
        $this->setPlayer($event->getPlayer());
        $this->drawReport();
    }

    public function onWallTiled(WallTiledEvent $event): void
    {
        $this->writeln("\nWALL TILING\n");
        $this->setPlayer($event->getPlayer());
        $this->drawReport();
    }

    private function drawReport(): void
    {
        static $roundCount = 0;
        if (isset($this->round)) {
            $this->drawFactories($this->round->getFactories());
            $this->drawTable($this->round->getTable());
        }
        $this->drawPlayers();
        $this->writeln(str_repeat('_', 49).++$roundCount.str_repeat('_', 49));
        $this->wait();
    }

    private function setPlayer(Player $player): void
    {
        $this->players[spl_object_hash($player)] = $player;
    }

    private function writeln(string $message): void
    {
        $this->output->writeln($message);
    }

    private function write(string $message): void
    {
        $this->output->write($message);
    }

    private function getColorSymbol(string $color): string
    {
        switch ($color) {
            case '':
                return '💠';
            case Color::BLACK:
                return '🔳';
            case Color::BLUE:
                return '🟦';
            case Color::CYAN:
                return '🟩';
            case Color::RED:
                return '🟥';
            case Color::YELLOW:
                return '🟨';
        }
    }

    private function drawFactories(\App\Game\FactoryCollection $factories): void
    {
        foreach ($factories as $factory) {
            $this->write('|_');
            foreach ($factory->getTiles() as $tile) {
                $this->drawTile($tile);
            }
            $this->write(str_repeat('_.', 4 - $factory->getTilesCount()).'_|');
            $this->write('   ');
        }
        $this->writeln('');
    }

    private function drawTable(\App\Game\Table $table): void
    {
        $this->write('table -> _');
        if ($table->getMarker()) {
            $this->drawTile($table->getMarker());
        }
        foreach ($table->getCenterPileTiles() as $color => $tiles) {
            foreach ($tiles as $tile) {
                $this->drawTile($tile);
            }
        }
        $this->write('_');
        $this->writeln('');
    }

    private function drawTile(\App\Tile\Tile $tile): void
    {
        $this->write($this->getColorSymbol($tile->getColor()));
    }

    private function drawPlayers(): void
    {
        // board
        foreach (Board::getRowNumbers() as $rowNumber) {
            foreach ($this->players as $player) {
                $row = $player->getBoard()->getRow($rowNumber);
                $this->write(str_repeat('  ', 5 - $rowNumber));
                for ($j = 0; $j < $row->getEmptySlotsCount(); $j++) {
                    $this->write('★ ');
                }
                foreach ($row->getTiles() as $tile) {
                    $this->drawTile($tile);
                }
                $this->write(' | ');
                // wall
                foreach ($player->getBoard()->getPattern($row) as $k => $tile) {
                    if ($tile) {
                        $this->drawTile($tile);
                    } else {
                        $this->drawWallTile(self::EMPTY_SLOT_SIGNS[$k]);
                    }
                }

                $this->write("\t\t\t\t");
            }
            $this->writeln('');
        }
        $this->writeln('');

        // floor
        foreach ($this->players as $player) {
            $this->write('floor '.$player->getName().' -> _');
            foreach ($player->getBoard()->getFloorTiles() as $tile) {
                $this->drawTile($tile);
            }
            $this->write("_\t\t\t");
        }
        $this->writeln('');

        // scores
        foreach ($this->players as $player) {
            $this->write('score '.$player->getName().' -> '.$player->getScore());
            $this->write("\t\t\t");
        }
        $this->writeln('');

        $this->writeln(str_repeat('_', 45).'<info>round end</info>'.str_repeat('_', 45));
        $this->writeln('');
        $this->writeln('');
    }

    private function wait(): void
    {
        usleep(self::SECONDS_PAUSE_BETWEEN_MOVES);
    }

    private function drawWallTile($tile)
    {
        $this->write($tile);
    }
}
