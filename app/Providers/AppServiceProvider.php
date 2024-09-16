<?php

namespace App\Providers;

use App\Listeners\ConsoleReporter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Symfony\Component\Console\Output\OutputInterface',
            'Symfony\Component\Console\Output\ConsoleOutput');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
