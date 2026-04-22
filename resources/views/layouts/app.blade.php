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
    <body class="font-sans antialiased text-slate-900">
        <div class="pointer-events-none fixed inset-x-0 top-0 h-56 bg-[radial-gradient(circle_at_center,rgba(245,158,11,0.24),transparent_65%)]"></div>
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="mx-auto mt-6 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="brand-panel brand-enter px-6 py-5">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="brand-enter pb-12">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
