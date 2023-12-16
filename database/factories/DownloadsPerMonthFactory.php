<?php

namespace Database\Factories;

use App\Models\DownloadsPerMonth;
use App\Models\Version;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class DownloadsPerMonthFactory extends Factory
{
    protected $model = DownloadsPerMonth::class;

    public function definition(): array
    {
        $date = Carbon::parse($this->faker->dateTimeBetween('-2 years'));
        $version = $this->faker->randomElement(['v5.0.0', 'v5.1.0', 'v5.2.0', 'v5.3.0', 'v4.2.0', 'v4.1.0']);

        return [
            'version' => $version,
            'minor_version' => Version::minorVersion($version),
            'year' => $date->format('Y'),
            'month' => $date->format('m'),
            'date' => $date->format('Y-m'),
            'downloads' => $this->faker->numberBetween(10, 999999),
        ];
    }
}

