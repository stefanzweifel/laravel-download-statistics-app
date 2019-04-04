<?php

namespace App\Providers;

use App\DownloadsPerMonth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.app', function ($view) {

            $period = collect(CarbonInterval::months(1)->toPeriod(
                Carbon::parse('first day of this month')->subMonths(12),
                Carbon::parse('first day of this month')->subMonth()
            ))->reverse();

            $view->with('navMonths', $period);

            $view->with(
                'navLaravelVersions',
                DownloadsPerMonth::groupBy('minor_version')->pluck('minor_version')
            );
        });
    }
}
