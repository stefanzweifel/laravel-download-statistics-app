@extends('layouts.app')

@section('title', 'Laravel Download Statistics')

@section('content')

    <section class="py-12">
        <div class="container mx-auto flex flex-col items-center text-center">
            <h2 class="text-xl md:text-2xl lg:text-3xl italic font-bold md:w-1/2">
                Download Statistics for the most <br> popular PHP framework
            </h2>

            <a
                href="{{ route('downloads.byMonth', $availableMonths->first()->format('Y-m')) }}"
                class="btn bg-green-200 text-green-900 hover:bg-green-300 hover:text-gray-900 hover:no-underline mt-8 no-underline">
                Explore the stats for {{ $availableMonths->first()->format('F Y') }} &rarr;
            </a>
        </div>

    </section>

    <section class="bg-pattern py-4">
        <div class="container max-w-2xl mx-auto">

            <div class="md:flex">
                <div class="md:w-1/2">

                    <div class="mx-2 my-12 shadow-md bg-white p-4 border-t-8 border-gray-600 rounded">
                        <div class="">
                            <div class="flex justify-between">
                                <h4 class="font-bold uppercase text-gray-700 text-lg">Last Month</h4>
                            </div>
                            <div>
                                <p class="text-3xl font-bold">
                                    {{ format_download_numbers($downloadsLastMonth) }}
                                </p>
                                <p>Downloads</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="md:w-1/2">

                    <div class="mx-2 my-12 shadow-md bg-white p-4 border-t-8 border-gray-600 rounded">
                        <div class="">
                            <div class="flex justify-between">
                                <h4 class="font-bold uppercase text-gray-700 text-lg">Last 12 months</h4>
                            </div>
                            <div>
                                <p class="text-3xl font-bold">
                                    {{ format_download_numbers($downloadsLastYear) }}
                                </p>
                                <p>Downloads</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </section>


    <section class="bg-gray-900  py-4 md:py-12">
        <div class="container mx-auto lg:max-w-4xl px-4">

            <div class="text-white">
                <h2 class="font-bold text-2xl md:text-4xl mb-2">
                    Available Monthly Statistics
                </h2>
            </div>

            <div class="md:flex flex-wrap">
                @foreach($availableMonthsGroupedByYear as $year => $months)
                    <div class="md:w-1/2 lg:w-1/3">
                        <div class="mx-2">
                            <div class="mb-8 md:mb-12 shadow-md bg-white p-4 border-t-8 border-gray-600 rounded">
                                <h3 class="text-xl font-bold">{{ $year }}</h3>
                                <ul>
                                    @foreach($months as $month)
                                        <li>
                                            <a class="hover:text-gray-700" href="{{ route('downloads.byMonth', $month->format('Y-m')) }}">{{ $month->format('F Y') }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>



        </div>
    </section>

@endsection
