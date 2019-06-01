<?php

namespace App\Jobs;

use App\DownloadsPerMonth;
use App\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FetchDownloadsForVersionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $version;

    /**
     * @var Carbon
     */
    public $from;

    /**
     * @var Carbon
     */
    public $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $version, Carbon $from, Carbon $to)
    {
        $this->version = $version;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $downloads = array_get($this->fetchDataFromPackagist(), 'values.0', 0);

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
            'downloads' => $downloads,
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
