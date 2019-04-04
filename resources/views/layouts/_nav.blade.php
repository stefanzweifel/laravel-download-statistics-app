<nav class="bg-white border-b border-gray-400">

    <div class="container mx-auto md:flex justify-between py-4 px-4 relative">
        <div>
            <a href="/" class="font-bold no-underline hover:no-underline hover:text-gray-700">Laravel Download Statistics</a>
        </div>

        <div class="flex">

            <details class="mr-8 relative">
                <summary class="summary-hide-marker font-bold cursor-pointer">&#8609; by Version</summary>
                <details-menu role="menu" class="absolute w-32 mt-2 -ml-1 bg-white border border-gray-300 rounded overflow-hidden shadow">
                    <ul class=" text-sm">
                        @foreach($navLaravelVersions->reverse() as $version)
                            <li>
                                <a role="menuitem" class="text-black py-1 px-3 block no-underline hover:no-underline hover:text-white hover:bg-gray-700" href="{{ route('downloads.byVersion', $version) }}">{{ $version }}</a>
                            </li>
                        @endforeach
                    </ul>
                </details-menu>
            </details>

            <details class="relative">
                <summary class="summary-hide-marker font-bold cursor-pointer">&#8609; by Month</summary>
                <details-menu role="menu" class="absolute w-40 mt-2 -ml-1 bg-white border border-gray-300 rounded overflow-hidden shadow">
                    <ul class="text-sm">
                        @foreach($navMonths as $month)
                            <li>
                                <a role="menuitem" class="text-black py-1 px-3 block no-underline hover:no-underline hover:text-white hover:bg-gray-700" href="{{ route('downloads.byMonth', $month->format('Y-m')) }}">{{ $month->format('F Y') }}</a>
                            </li>
                        @endforeach
                    </ul>
                </details-menu>
            </details>
        </div>
    </div>

</nav>
