<?php

use Carbon\Carbon;
use Carbon\CarbonInterval;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('rebuild-everything', function () {

    // Build Timeperiod for which data should be rebuilt
    $period = collect(CarbonInterval::months(1)->toPeriod(
        Carbon::parse('2013-05'),
        Carbon::parse('first day of this month')->subMonth()
    ));

    // Loop through period and call artisan command to fetch data
    foreach($period as $date) {
        $this->info("Fetch downloads for {$date->format('F Y')}.");

        Artisan::call("app:fetch-downloads --date={$date->format('Y-m')}");
    }

    $this->info('Done');

});
