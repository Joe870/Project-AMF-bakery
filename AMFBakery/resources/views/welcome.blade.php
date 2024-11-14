<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
{{--            <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />--}}
{{--            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">--}}
{{--                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">--}}
                    <nav class="grid place-items-center p-3">
                        @if (Route::has('login'))
                            <livewire:welcome.navigation />
                        @endif
                    </nav>

                    <main class="mt-6">

                    </main>

                </div>
{{--            </div>--}}
{{--        </div>--}}
    </body>
</html>
