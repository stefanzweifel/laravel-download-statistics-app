<?php

namespace Tests\Feature\Http;

use App\Models\DownloadsPerMonth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DownloadsByVersionControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_throws_404_error_if_no_statistics_data_is_available_for_given_version()
    {
        $response = $this
            ->get(route('downloads.byVersion', 'v1.0'))
            ->assertStatus(404);
    }

    #[Test]
    public function it_returns_statistics_data_grouped_by_minor_version_for_given_version()
    {
        DownloadsPerMonth::factory()->create([
            'version' => 'v5.1.1',
            'minor_version' => 'v5.1',
            'downloads' => 30,
            'date' => now()->subMonths(1)->format('Y-m'),
        ]);
        DownloadsPerMonth::factory()->create([
            'version' => 'v5.0.0',
            'minor_version' => 'v5.0',
            'downloads' => 10,
            'date' => now()->subMonths(2)->format('Y-m'),
        ]);
        DownloadsPerMonth::factory()->create([
            'version' => 'v5.0.1',
            'minor_version' => 'v5.0',
            'downloads' => 10,
            'date' => now()->subMonths(1)->format('Y-m'),
        ]);
        DownloadsPerMonth::factory()->create([
            'version' => 'v5.0.2',
            'minor_version' => 'v5.0',
            'downloads' => 20,
            'date' => now()->subMonths(1)->format('Y-m'),
        ]);
        DownloadsPerMonth::factory()->create([
            'version' => 'v5.0.3',
            'minor_version' => 'v5.0',
            'downloads' => 30,
            'date' => now()->subMonths(1)->format('Y-m'),
        ]);

        $response = $this
            ->get(route('downloads.byVersion', 'v5.0'))
            ->assertStatus(200)
            ->assertViewHas('downloadsHistory');

        $downloadsHistory = $response->data('downloadsHistory');

        $this->assertEquals(70, $downloadsHistory->sum('downloads'));

        $this->assertEquals([
            [
                'minor_version' => 'v5.0',
                'date' => now()->subMonths(2)->format('Y-m'),
                'downloads' => 10,
            ],
            [
                'minor_version' => 'v5.0',
                'date' => now()->subMonths(1)->format('Y-m'),
                'downloads' => 60,
            ],
        ], $downloadsHistory->toArray());
    }
}
