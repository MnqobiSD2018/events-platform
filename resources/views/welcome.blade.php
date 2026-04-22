<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} – Corporate Event Management Platform</title>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased">
        <nav class="sticky top-0 z-30 border-b border-white/60 bg-white/75 backdrop-blur">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="inline-flex items-center gap-2">
                            <x-application-logo class="h-9 w-auto fill-current text-teal-700" />
                            <span class="hidden sm:inline font-semibold text-slate-900">Event Platform</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-6">
                        @auth
                            <a href="{{ route('home') }}" class="inline-flex items-center rounded-lg border border-teal-100 bg-teal-50/70 px-4 py-2 text-sm font-medium text-teal-900 hover:bg-teal-100/80 transition ease-in-out duration-150">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-teal-700 transition ease-in-out duration-150">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-600 transition duration-150 ease-in-out">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <section class="relative py-16 sm:py-24 lg:py-32">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center brand-enter">
                        <p class="brand-kicker justify-center">Event Management Made Simple</p>
                        <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-slate-900">
                            Plan. Scan. Measure.
                        </h1>
                        <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto">
                            A complete platform for managing corporate events. Organize participants, track attendance with QR codes, and measure success with real-time analytics.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                            @auth
                                <a href="{{ route('home') }}" class="inline-flex items-center rounded-lg border border-teal-700 bg-teal-700 px-6 py-3 text-sm font-semibold text-white hover:bg-teal-600 transition duration-150">
                                    Open Dashboard →
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg border border-teal-700 bg-teal-700 px-6 py-3 text-sm font-semibold text-white hover:bg-teal-600 transition duration-150">
                                    Get Started Free
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition duration-150">
                                    Sign In
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative py-16 sm:py-24 border-t border-white/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="brand-panel p-6 brand-enter">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-teal-600 to-teal-700">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Event Management</h3>
                            <p class="mt-2 text-sm text-slate-600">Create, edit, and manage corporate events with full control over participants and schedules.</p>
                        </div>

                        <div class="brand-panel p-6 brand-enter">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">QR Check-in</h3>
                            <p class="mt-2 text-sm text-slate-600">Scan participant QR codes in real-time to track attendance instantly and prevent duplicates.</p>
                        </div>

                        <div class="brand-panel p-6 brand-enter">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-600 to-emerald-700">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Analytics & Reports</h3>
                            <p class="mt-2 text-sm text-slate-600">Get detailed attendance reports with percentages and participant-level insights.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative py-16 sm:py-24 bg-gradient-to-br from-teal-50 to-emerald-50 border-t border-white/60">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <p class="brand-kicker justify-center">Built for Teams</p>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-slate-900">
                        Everything you need for successful events
                    </h2>
                    <ul class="mt-8 space-y-3 text-left">
                        <li class="flex items-center gap-3 text-slate-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Bulk participant uploads via CSV
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Automatic unique QR code generation
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Mobile-friendly QR scanner
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Real-time attendance tracking
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Detailed analytics and reporting
                        </li>
                    </ul>
                </div>
            </section>

            <section class="relative py-16 sm:py-24 border-t border-white/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <p class="brand-kicker justify-center">Ready to get started?</p>
                        <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-slate-900">
                            Join companies using our platform
                        </h2>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        @guest
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg border border-teal-700 bg-teal-700 px-8 py-3 text-base font-semibold text-white hover:bg-teal-600 transition duration-150">
                                Create Free Account
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-8 py-3 text-base font-semibold text-slate-700 hover:bg-slate-50 transition duration-150">
                                Already have an account?
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="inline-flex items-center rounded-lg border border-teal-700 bg-teal-700 px-8 py-3 text-base font-semibold text-white hover:bg-teal-600 transition duration-150">
                                Go to Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </section>
        </main>

        <footer class="mt-24 border-t border-white/60 py-12 text-center text-sm text-slate-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} Event Platform. Built with Laravel and modern web technologies.</p>
            </div>
        </footer>
    </body>
</html>