<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top_left,rgba(15,118,110,0.22),transparent_45%),radial-gradient(circle_at_bottom_right,rgba(245,158,11,0.26),transparent_40%)]"></div>
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center px-4 py-8">
            <div>
                <a href="/">
                    <x-application-logo class="h-20 w-20 fill-current text-teal-700" />
                </a>
            </div>

            <div class="brand-panel brand-enter mt-6 w-full px-6 py-5 sm:max-w-md">
                <p class="brand-kicker">Admin Portal</p>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
