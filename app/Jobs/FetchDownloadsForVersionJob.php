<?php

namespace App\Jobs;

use App\DownloadsPerMonth;
use App\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FetchDownloadsForVersionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $version;

    /**
     * @var \Carbon\Carbon
     */
    public $from;

    /**
     * @var \Carbon\Carbon
     */
    public $to;

    public function __construct(string $version, Carbon $from, Carbon $to)
    {
        $this->version = $version;
        $this->from = $from;
        $this->to = $to;
    }

    public function handle()
    {
        $downloads = collect(Arr::get($this->fetchDataFromPackagist(), 'values'))->first()[0];

        Log::debug("version: {$this->version} - month: {$this->from->format('Y-m')} - downloads: {$downloads}");

        if ($downloads === 0) {
            return;
        }

        $this->storeDownloads($downloads);
    }

    private function storeDownloads(int $downloads): void
    {
        DownloadsPerMonth::updateOrCreate([
            'version' => $this->version,
            'minor_version' => Version::minorVersion($this->version),
            'year' => $this->from->format('Y'),
            'month' => $this->from->format('m'),
            'date' => $this->from->format('Y-m'),
        ], [
            'version' => $this->version,
            'minor_version' => Version::minorVersion($this->version),
            'year' => $this->from->format('Y'),
            'month' => $this->from->format('m'),
            'date' => $this->from->format('Y-m'),

            // The number in $downlaods is the number of *daily* installs.
            // So we multiply the number of downloads by the number of days in the month
            // to get to the average total number of downloads for the month.
            'downloads' => $downloads * $this->from->daysInMonth,
        ]);
    }

    private function fetchDataFromPackagist(): array
    {
        $from = $this->from->format('Y-m-d');
        $to = $this->to->format('Y-m-d');

        $url = "https://packagist.org/packages/laravel/framework/stats/{$this->version}.json?average=monthly&from={$from}&to={$to}";

        return Cache::rememberForever(md5($url), function () use ($url) {
            return json_decode(file_get_contents($url), true);
        });
    }
}
