<?php

namespace App\Console\Commands;

use App\Jobs\FetchDownloadsForVersionJob;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
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
    protected $signature = 'app:fetch-downloads {--date=} {--all=false}';

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
        $all = $this->option('all');

        if ($all !== 'false') {
            $this->fetchVersionsForAllMonths();
            return;
        }

        if (is_null($date)) {
            $fromDate = Carbon::parse('first day of last month');
            $toDate = Carbon::parse('last day of last month');
        } else {
            $fromDate = Carbon::parse("first day of {$date}");
            $toDate = Carbon::parse("last day of {$date}");
        }

        $this->fetchVersionsAndDispatchJobs($fromDate, $toDate);
    }

    private function fetchVersionsForAllMonths(): void
    {
        $period = collect(CarbonInterval::months(1)->toPeriod(
            Carbon::parse('first day of May 2013'),
            Carbon::parse('last day of last month')
        ))->reverse();

        $period->each(function ($date) {
            $fromDate = $date->copy()->firstOfMonth();
            $toDate = $date->copy()->lastOfMonth();

            $this->fetchVersionsAndDispatchJobs($fromDate, $toDate);
        });
    }

    private function fetchVersionsAndDispatchJobs(Carbon $fromDate, Carbon $toDate): void
    {
        $this->getNormalizedLaravelVersions()->each(function ($version) use ($fromDate, $toDate) {
            dispatch(new FetchDownloadsForVersionJob($version, $fromDate, $toDate));
        });
    }

    private function getNormalizedLaravelVersions(): Collection
    {
        $versions = collect(Arr::get($this->getResponseFromPackagist(), 'package.versions'));

        // Only keep version in the format v.0.0.0
        // (ignores beta or dev releases)
        return $versions
            ->keys()
            ->filter(function ($version) {
                $pattern = '/^v([4-9])\.(\d)*\.(\d)*$/';
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
