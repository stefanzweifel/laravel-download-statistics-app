<?php

namespace App\Http\Controllers;

use App\DataSets\DownloadsByVersionDataSet;
use App\DownloadsPerMonth;
use Illuminate\Support\Carbon;

class DownloadsByMonthController extends Controller
{
    public function __invoke(string $date, DownloadsPerMonth $downloadsPerMonth, DownloadsByVersionDataSet $dataSet)
    {
        $downloads = $downloadsPerMonth->getDownloadsGroupedByMinorVersion($date);

        abort_if($downloads->count() === 0, 404);

        $totalDownloads = $downloads->sum('downloads');
        $downloads = $downloads->map(function ($version) use ($totalDownloads) {
            return $this->appendDataToSingleVersion($version, $totalDownloads);
        });

        $mostPopular = $downloads->sortByDesc('downloads')->values()->first();

        return view('downloads.byMonth')->with([
            'date' => Carbon::parse($date),
            'mostPopular' => $mostPopular,
            'downloadsHistory' => $downloads,
            'downloadsHistoryDataSet' => $dataSet->get($downloads, 'date')->first()['values'],
        ]);
    }

    private function appendDataToSingleVersion(DownloadsPerMonth $version, int $totalDownloads): DownloadsPerMonth
    {
        $previousMonth = app(DownloadsPerMonth::class)
            ->getDownloadsByMinorVersionAndByMonth(
                $version->minor_version,
                Carbon::parse($version->date)->subMonth()->format('Y-m')
            )->first();

        $version['percentage'] = $version->downloads / $totalDownloads * 100;
        $version['previous_month'] = $previousMonth;

        if ($previousMonth) {
            $version['change_to_previous_month_percentage'] = ($version->downloads - $previousMonth->downloads) / $previousMonth->downloads * 100;
        } else {
            $version['change_to_previous_month_percentage'] = null;
        }

        return $version;
    }
}
