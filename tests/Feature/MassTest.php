<?php

test('console command', function () {
    foreach (range(1, 10) as $i) {
        $this->artisan('app:play')->assertExitCode(0);
        \Illuminate\Support\Facades\Log::info('test run: '.$i);
    }
});
