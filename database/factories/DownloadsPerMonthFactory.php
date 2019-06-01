<?php

use App\DownloadsPerMonth;
use App\Version;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(DownloadsPerMonth::class, function (Faker $faker) {
    $date = Carbon::parse($faker->dateTimeBetween('-2 years'));
    $version = $faker->randomElement(['v5.0.0', 'v5.1.0', 'v5.2.0', 'v5.3.0', 'v4.2.0', 'v.4.1.0']);

    return [
        'version' => $version,
        'minor_version' => Version::minorVersion($version),
        'year' => $date->format('Y'),
        'month' => $date->format('m'),
        'date' => $date->format('Y-m'),
        'downloads' => $faker->numberBetween(10, 999999),
    ];
});
