<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DownloadsPerMonth extends Model
{
    protected $fillable = [
        'version',
        'minor_version',
        'year',
        'month',
        'date',
        'downloads'
    ];

    protected $casts = [
        'downloads' => 'integer'
    ];

    public function scopeInTheLastYear(Builder $query) : Builder
    {
        return $query->where('date', '>=', now()->subMonths(12)->format('Y-m'));
    }


    public function getDownloadsGroupedByMinorVersion(string $date)
    {
        return self::query()
            ->where('date', $date)
            ->groupBy('date')
            ->groupBy('minor_version')
            ->get([
                'minor_version',
                'date',
                DB::raw('sum(downloads) as downloads')
            ]);
    }

    public function getDownloadsPerMonthByMinorVersion(string $version)
    {
        return self::query()
            ->where('minor_version', $version)
            ->groupBy('date')
            ->groupBy('minor_version')
            ->get([
                'minor_version',
                'date',
                DB::raw('sum(downloads) as downloads')
            ]);
    }

    public function getDownloadsByMinorVersionAndByMonth(string $version, string $month)
    {
        return self::query()
            ->where('minor_version', $version)
            ->where('date', $month)
            ->groupBy('minor_version')
            ->get([
                'minor_version',
                'date',
                DB::raw('sum(downloads) as downloads')
            ]);
    }
}
