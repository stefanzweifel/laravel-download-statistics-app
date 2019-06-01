<?php

namespace App\DataSets;

use Illuminate\Support\Collection;

class DownloadsByVersionDataSet
{
    public function get(Collection $downloadsByVersion, $versionType = 'minor_version')
    {
        $maxNumberOfDataPoints = $this->getMaxNumberOfDataPoints();

        return $downloadsByVersion
            ->groupBy($versionType)
            ->map(function ($values, $key) use ($maxNumberOfDataPoints) {

                // 'values' should be left-padded to contain as many values, as the minor_version with the most results
                // otherwhise the download numbers are misaligned

                return [
                    'name' => $key,
                    'values' => $values->pluck('downloads')->pad(-1 * $maxNumberOfDataPoints, 0),
                ];
            })
            ->values();
    }

    private function getMaxNumberOfDataPoints(Collection $downloadsByVersion, string $versionType)
    {
        return $downloadsByVersion
            ->groupBy($versionType)
            ->map(function ($version) {
                return $version->count();
            })
            ->values()
            ->max();
    }
}
