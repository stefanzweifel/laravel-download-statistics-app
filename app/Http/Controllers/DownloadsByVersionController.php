<?php

namespace App\Http\Controllers;

use App\DataSets\DownloadsByVersionDataSet;
use App\DownloadsPerMonth;
use App\Version;

class DownloadsByVersionController extends Controller
{
    public function __invoke(string $version, DownloadsPerMonth $model, DownloadsByVersionDataSet $dataSet)
    {
        abort_unless(Version::isLaravelVersion($version), 404);

        $downloads = $model->getDownloadsPerMonthByMinorVersion($version);

        return view('downloads.byVersion')->with([
            'version' => $version,
            'downloadsHistory' => $downloads,
            'downloadsHistoryDataSet' => $dataSet->get($downloads),
        ]);
    }
}
