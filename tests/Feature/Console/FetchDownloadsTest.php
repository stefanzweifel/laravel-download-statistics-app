<?php

namespace Tests\Feature\Console;

use App\Jobs\FetchDownloadsForVersionJob;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FetchDownloadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_jobs_to_get_download_numbers_for_all_laravel_versions()
    {
        Queue::fake();

        $this->artisan('app:fetch-downloads');

        Queue::assertPushed(FetchDownloadsForVersionJob::class, function ($job) {
            return $job->version === 'v5.0.0' &&
                $job->from->format('Y-m') == Carbon::parse('first day of this month')->subMonth()->format('Y-m');
        });
        Queue::assertPushed(FetchDownloadsForVersionJob::class, function ($job) {
            return $job->version === 'v5.1.0' &&
                $job->from->format('Y-m') == Carbon::parse('first day of this month')->subMonth()->format('Y-m');
        });
    }

    /** @test */
    public function it_dispatches_jobs_to_get_download_numbers_and_uses_given_date()
    {
        Queue::fake();

        $this->artisan('app:fetch-downloads', ['--date' => '2018-12']);

        Queue::assertPushed(FetchDownloadsForVersionJob::class, function ($job) {
            return $job->version === 'v5.0.0' &&
                $job->from->format('Y-m') == '2018-12';
        });
        Queue::assertPushed(FetchDownloadsForVersionJob::class, function ($job) {
            return $job->version === 'v5.1.0' &&
                $job->from->format('Y-m') == '2018-12';
        });
    }
}
