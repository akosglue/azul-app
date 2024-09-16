<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Board\Board;
use App\Game\Bag;
use App\Game\Game;
use App\Listeners\ConsoleReporter;
use App\Player\Player;
use App\Player\PlayerCollection;
use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;

class PlayGameCommand extends Command
{
    protected $signature = 'app:play';

    protected $description = 'Starts a new game.';

    /**
     * Execute the console command.
     */
    public function handle(Dispatcher $dispatcher)
    {
		$this->info('Let\'s start!');

		$players = new PlayerCollection([
			new Player(new Board(), 'Ivan', ),
			new Player(new Board(), 'Petr', ),
		]);

        $dispatcher->subscribe(new ConsoleReporter($players,$this->output));
		$game = new Game(Bag::create(),$dispatcher);

		$game->play($players);

		return 0;
	}
}
