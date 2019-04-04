<?php

namespace App\Http\Controllers;

use App\DownloadsPerMonth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function __invoke(Request $request)
    {
        $availableMonths = collect(CarbonInterval::months(1)->toPeriod(
            Carbon::parse('2013-05-01'),
            Carbon::parse('first day of this month')->subMonth()
        ))
        ->reverse();

        $availableMonthsGroupedByYear = $availableMonths->groupBy(function ($month) {
            return $month->format('Y');
        });


        $downloadsLastMonth = DownloadsPerMonth::query()
                ->where('date', Carbon::parse('first day of this month')->subMonth()->format('Y-m'))
                ->get()
                ->sum('downloads');

        $downloadsLastYear = DownloadsPerMonth::query()
                ->where('date', '>=', Carbon::parse('first day of this month')->subMonths(12)->format('Y-m'))
                ->get()
                ->sum('downloads');

        return view('home', compact('downloadsLastMonth', 'downloadsLastYear', 'availableMonths', 'availableMonthsGroupedByYear'));
    }
}
