<?php

namespace Tests\Feature\Http;

use App\DownloadsPerMonth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownloadsByMonthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_404_error_if_no_downloads_are_available_for_given_month()
    {
        $response = $this->get(route('downloads.byMonth', '2019-01'))
            ->assertStatus(404);
    }

    /** @test */
    public function it_returns_statistics_data_for_given_month()
    {

        factory(DownloadsPerMonth::class)->create([
            'version' => 'v5.1.1',
            'minor_version' => 'v5.1',
            'downloads' => 30,
            'date' => '2019-01'
        ]);
        factory(DownloadsPerMonth::class)->create([
            'version' => 'v5.0.0',
            'minor_version' => 'v5.0',
            'downloads' => 10,
            'date' => '2019-01'
        ]);

        $response = $this->get(route('downloads.byMonth', '2019-01'))
            ->assertStatus(200)
            ->assertViewHas('downloadsHistory');

        $downloadsHistory = $response->data('downloadsHistory');

        $this->assertEquals(40, $downloadsHistory->sum('downloads'));

        $this->assertEquals([
            [
                'minor_version' => 'v5.0',
                'date' => '2019-01',
                'downloads' => 10,
                'percentage' => 25,
                'previous_month' => null,
                'change_to_previous_month_percentage' => null,
            ],
            [
                'minor_version' => 'v5.1',
                'date' => '2019-01',
                'downloads' => 30,
                'percentage' => 75,
                'previous_month' => null,
                'change_to_previous_month_percentage' => null,
            ]
        ], $downloadsHistory->toArray());

    }

}
