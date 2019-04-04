@extends('layouts.app')

@section('title', "Downloads for Laravel {$version}")

@section('content')

    <div class="container mx-auto py-6 bg-gray-100 px-4 md:px-0">

        <div class="md:flex md:mt-8 mb-16">

            <div class="md:w-1/2">
                <h2 class="text-5xl md:text-6xl font-bold">Laravel {{ $version }}</h2>
                <p class="text-xl md:text-2xl">
                    Laravel <mark class="font-bold bg-yellow-200">{{ $version }}</mark> has been downloaded
                    <mark class="font-bold bg-yellow-200">
                        {{ format_download_numbers($downloadsHistory->sum('downloads') ) }}
                    </mark> times so far.
                </p>

                <a href="https://github.com/stefanzweifel/laravel-download-statistics-app#how-are-numbers-calculated" target="_blank" class="my-4 inline-block hover:text-gray-700">
                    How are these numbers calculated?
                </a>

            </div>
            <div class="md:w-1/2">

                @if ($downloadsHistory->count() > 1)
                    <canvas id="chart-downloads-over-time" class="my-8 md:my-0"></canvas>
                @else
                    <div class="bg-gray-300 h-56 flex justify-center items-center italic">
                        <p>Not Enough <span class="line-through">Minerals</span> Data!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script>
        var ctx = document.getElementById('chart-downloads-over-time');
        var chartDownloadsOverTime = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $downloadsHistory->pluck('date')->unique()->values()->map(function ($date) {
                    return \Illuminate\Support\Carbon::parse($date)->format('F Y');
                })->toJson() !!},
                datasets: [
                    {
                        label: '# of Downloads',
                        data: {!! $downloadsHistoryDataSet->first()['values']->toJson() !!},
                        backgroundColor: [
                            'rgba(245, 82, 71, 0.3)'
                        ],
                        borderColor: [
                            'rgba(245, 82, 71, 0.9)'
                        ],
                        borderWidth: 3
                    }
                ]
            },
            options: {
                elements: {
                    line: {
                        tension: 0.1
                    }
                },
                legend: {
                    display: false,
                },
            }
        });

    </script>

@endsection
