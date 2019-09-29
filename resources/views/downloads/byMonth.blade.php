@extends('layouts.app')

@section('title', "Downloads in {$date->format('F Y')}")

@section('content')

    <section>
        <div class="container mx-auto py-6 bg-gray-100 px-4">


        <div class="md:flex md:mt-8 mb-16">
            <div class="md:w-1/2 lg:w-2/3 mb-4 md:mb-0">
                <h2 class="text-5xl md:text-6xl font-bold">{{ $date->format('F Y') }}</h2>
                <p class="text-xl md:text-2xl mb-2">
                    Laravel <a href="{{ route('downloads.byVersion', $mostPopular->minor_version) }}" class="font-bold bg-yellow-200">{{ $mostPopular->minor_version }}</a> was the most popular
                    version in {{ $date->format('F Y') }}. It has been downloaded
                    <mark class="whitespace-no-wrap font-bold bg-yellow-200 px-1">
                        {{ format_download_numbers($mostPopular->downloads) }}
                    </mark>
                    times.
                    Thats
                    <mark class="whitespace-no-wrap font-bold bg-yellow-200 px-1">
                        {{ round($mostPopular->percentage, 1) }}%
                    </mark>
                    of all downloads!
                </p>

                <p class="text-xl md:text-2xl">
                    In total, Laravel has been downloaded
                    <mark class="whitespace-no-wrap font-bold bg-yellow-200 px-1">
                        {{ format_download_numbers($downloadsHistory->sum('downloads')) }}
                    </mark>
                     times this month.
                </p>

                <a href="https://github.com/stefanzweifel/laravel-download-statistics-app#how-are-numbers-calculated" target="_blank" class="my-4 inline-block hover:text-gray-700">
                    How are these numbers calculated?
                </a>

                <div class="flex mt-2">

                    <a href="{{ route('downloads.byMonth', $date->copy()->subMonth()->format('Y-m')) }}" class="no-underline btn bg-gray-300 text-black mt-4 mr-4 text-xs md:text-base">
                        &larr; Previous Month
                    </a>

                    @if ($date->copy()->addMonth()->format('Y-m') !== now()->format('Y-m'))
                        <a href="{{ route('downloads.byMonth', $date->copy()->addMonth()->format('Y-m')) }}" class="no-underline btn bg-gray-300 text-black mt-4 text-xs md:text-base">
                            Next Month &rarr;
                        </a>
                    @endif
                </div>
            </div>
            <div class="md:w-1/2 lg:w-1/3">
                <canvas id="myChart" width="100" height="100"></canvas>
            </div>
        </div>


        </div>
    </section>

    <section class="bg-gray-200">
        <div class="container mx-auto lg:max-w-xl py-6 md:py-12 px-4">
            <h2 class="font-bold text-4xl leading-none mb-4">Version Breakdown</h2>

            @foreach($downloadsHistory->reverse() as $version)

                <div class="flex justify-between max-w-xl bg-white my-2 p-4 rounded shadow border border-gray-300">

                    <div class="w-1/4">
                        <a href="{{ route('downloads.byVersion', $version->minor_version) }}">{{ $version->minor_version }}</a>
                    </div>
                    <div class="w-1/4 text-left">
                        @if ($version->previous_month)
                            @if ($version['change_to_previous_month_percentage'] > 0)

                                <span class="text-sm rounded-full font-bold bg-green-200 text-green-800 py-1 px-2">
                                    + {{ round($version['change_to_previous_month_percentage']) }} %
                                </span>
                            @else
                                <span class="text-sm rounded-full font-bold bg-red-200 text-red-800 py-1 px-2">
                                    {{ round($version['change_to_previous_month_percentage']) }} %
                                </span>
                            @endif
                        @endif
                    </div>
                    <div class="w-1/4 text-right">
                        {{ format_download_numbers($version->downloads) }}

                        ({{ round($version->percentage) }}%)
                    </div>
                    <div class="w-1/4 text-right">
                        @if ($version->previous_month)
                            <span class="text-sm text-gray-600">
                                {{ format_download_numbers($version['previous_month']['downloads']) }}
                            </span>
                        @else
                            <span class="text-gray-600 text-sm">
                                N/A
                            </span>
                        @endif
                    </div>
                </div>

            @endforeach
        </div>
    </section>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script>
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! $downloadsHistory->pluck('minor_version')->unique()->values()->toJson() !!},
                datasets: [
                    {
                        label: '# of Downloads',
                        data: {!! $downloadsHistoryDataSet->toJson() !!},
                        backgroundColor: [
                            'hsl(27, 84%, 80%, 0.8)',
                            'hsl(27, 84%, 40%, 0.8)',
                            'hsl(27, 84%, 50%, 0.8)',
                            'hsl(247, 33%, 15%, 0.8)',
                            'hsl(247, 33%, 20%, 0.8)',
                            'hsl(247, 33%, 25%, 0.8)',
                            'hsl(247, 33%, 30%, 0.8)',
                            'hsl(247, 33%, 35%, 0.8)',
                            'hsl(247, 33%, 40%, 0.8)',
                            'hsl(247, 33%, 45%, 0.8)',
                            'hsl(247, 33%, 50%, 0.8)',
                            'hsl(247, 33%, 55%, 0.8)',
                            'hsl(247, 33%, 60%, 0.8)',
                            'hsl(247, 33%, 65%, 0.8)',
                            'hsl(247, 33%, 70%, 0.8)',
                        ],
                        borderColor: [
                            'hsl(27, 84%, 80%, 1)',
                            'hsl(27, 84%, 40%, 1)',
                            'hsl(27, 84%, 50%, 1)',
                            'hsl(247, 33%, 15%, 1)',
                            'hsl(247, 33%, 20%, 1)',
                            'hsl(247, 33%, 25%, 1)',
                            'hsl(247, 33%, 30%, 1)',
                            'hsl(247, 33%, 35%, 1)',
                            'hsl(247, 33%, 40%, 1)',
                            'hsl(247, 33%, 45%, 1)',
                            'hsl(247, 33%, 50%, 1)',
                            'hsl(247, 33%, 55%, 1)',
                            'hsl(247, 33%, 60%, 1)',
                            'hsl(247, 33%, 65%, 1)',
                            'hsl(247, 33%, 70%, 1)',
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                layout: {
                    padding: {
                        top: 10,
                        right: 10,
                        bottom: 10,
                        left: 10
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                },
                scales: {

                }
            }
        });

    </script>

@endsection
