<?php

namespace App\Console\Commands;

use App\Jobs\FetchDownloadsForVersionJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FetchDownloads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-downloads {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch downloads data from packagist.org';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->option('date');

        if (is_null($date)) {
            $fromDate = Carbon::parse('first day of last month');
            $toDate = Carbon::parse('last day of last month');
        } else {
            $fromDate = Carbon::parse("first day of {$date}");
            $toDate = Carbon::parse("last day of {$date}");
        }

        $this->fetchVersionsAndDispatchJobs($fromDate, $toDate);
    }

    private function fetchVersionsAndDispatchJobs($fromDate, $toDate): void
    {
        $this->getNormalizedLaravelVersions()->each(function ($version) use ($fromDate, $toDate) {
            dispatch(new FetchDownloadsForVersionJob($version, $fromDate, $toDate));
        });
    }

    private function getNormalizedLaravelVersions(): Collection
    {
        $versions = collect(array_get($this->getResponseFromPackagist(), 'package.versions'));

        // Only keep version in the format v.0.0.0
        // (ignores beta or dev releases)
        return $versions
            ->keys()
            ->filter(function ($version) {
                $pattern = '/^v([4-5])\.(\d)*\.(\d)*$/';
                return preg_match($pattern, $version);
            })
            ->values();
    }

    private function getResponseFromPackagist(): array
    {
        $url = 'https://packagist.org/packages/laravel/framework.json';

        return Cache::remember(md5($url), now()->addHour(), function () use ($url) {
            if (app()->environment('testing')) {
                $url = base_path('tests/stub/packages-laravel-framework.json');
            }

            return json_decode(file_get_contents($url), true);
        });
    }
}
