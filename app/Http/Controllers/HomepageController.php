<?php

namespace App\Http\Controllers;

use App\DownloadsPerMonth;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class HomepageController extends Controller
{
    public function __invoke()
    {
        $availableMonths = collect(CarbonInterval::months(1)->toPeriod(
            Carbon::parse('2013-05-01'),
            Carbon::parse('first day of this month')->subMonth()
        ))
        ->reverse();

        $availableMonthsGroupedByYear = $availableMonths->groupBy(function ($month) {
            return $month->format('Y');
        });

        return view('home')->with([
            'downloadsLastMonth' => $this->downloadsLastMonth(),
            'downloadsLastYear' => $this->downloadsLastYear(),
            'availableMonths' => $availableMonths,
            'availableMonthsGroupedByYear' => $availableMonthsGroupedByYear,
        ]);
    }

    private function downloadsLastMonth(): int
    {
        return DownloadsPerMonth::query()
            ->where('date', Carbon::parse('first day of this month')->subMonth()->format('Y-m'))
            ->get()
            ->sum('downloads');
    }

    private function downloadsLastYear(): int
    {
        return DownloadsPerMonth::query()
            ->where('date', '>=', Carbon::parse('first day of this month')->subMonths(12)->format('Y-m'))
            ->get()
            ->sum('downloads');
    }
}
