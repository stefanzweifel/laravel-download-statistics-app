<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:400,700" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="flex flex-col justify-between min-h-screen">


        @include('layouts._nav')

        <div class="bg-red-100 border-t border-b border-red-400 text-red-700 lg:text-center" role="alert">
            <div class="container mx-auto px-4 py-2">
                <span class="block sm:inline">
                    Packagist updated their download statistics, which make this project redundant. Check out Laravel's download statistics on
                    <a href="https://packagist.org/packages/laravel/framework/stats#major/all" target="_blank">
                        packagist.org
                    </a>.
                </span>
            </div>
        </div>

        <main class="flex-1 bg-gray-100">
            @yield('content')
        </main>


        <footer class="bg-white border-t border-gray-400">
            <div class="container lg:max-w-4xl mx-auto py-8 px-4 md:px-0">
                <div class="md:flex items-center justify-between">
                    <div class="text-sm mb-4 md:mb-0">
                        <p class="mb-1">
                            Download numbers have been last updated at <mark class="bg-yellow-200">{{ now()->format('jS F, Y') }}</mark>.
                        </p>
                        <p>
                            A project by <a href="https://twitter.com/_stefanzweifel" target="_blank">Stefan Zweifel</a>.
                        </p>
                    </div>
                    <div class="text-sm md:w-1/3 flex justify-between">

                        <ul class="mr-2">
                            <li>
                                <a href="https://github.com/stefanzweifel/laravel-download-statistics-app#how-are-numbers-calculated" target="_blank" class="hover:text-gray-700">How are Numbers calculated</a>
                            </li>
                        </ul>

                        <ul>
                            <li>
                                <a href="https://github.com/stefanzweifel/laravel-download-statistics-app" target="_blank" class="hover:text-gray-700">Github</a>
                            </li>

                        </ul>

                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>
</html>
