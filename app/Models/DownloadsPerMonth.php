<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DownloadsPerMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'minor_version',
        'year',
        'month',
        'date',
        'downloads',
    ];

    protected $casts = [
        'downloads' => 'integer',
    ];

    public function scopeInTheLastYear(Builder $query): Builder
    {
        return $query->where('date', '>=', now()->subMonths(12)->format('Y-m'));
    }

    public function getDownloadsGroupedByMinorVersion(string $date): Collection
    {
        return self::query()
            ->where('date', $date)
            ->groupBy('date')
            ->groupBy('minor_version')
            ->get([
                'minor_version',
                'date',
                DB::raw('sum(downloads) as downloads'),
            ]);
    }

    public function getDownloadsPerMonthByMinorVersion(string $version): Collection
    {
        return self::query()
            ->where('minor_version', $version)
            ->groupBy('date')
            ->groupBy('minor_version')
            ->get([
                'minor_version',
                'date',
                DB::raw('sum(downloads) as downloads'),
            ]);
    }

    public function getDownloadsByMinorVersionAndByMonth(string $version, string $month): Collection
    {
        return self::query()
            ->where('minor_version', $version)
            ->where('date', $month)
            ->groupBy('minor_version')
            ->groupBy('date')
            ->get([
                'minor_version',
                'date',
                DB::raw('sum(downloads) as downloads'),
            ]);
    }
}
