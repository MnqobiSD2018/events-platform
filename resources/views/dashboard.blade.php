<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Control Center</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">{{ __('Event Operations Dashboard') }}</h2>
            </div>
            <p class="text-sm font-medium text-slate-500">Plan. Scan. Measure.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="brand-panel p-6">
                    <p class="text-sm font-medium text-slate-500">Total Events Created</p>
                    <h3 class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalEvents }}</h3>
                    <p class="mt-2 text-sm text-slate-600">All events created under your account.</p>
                </div>

                <a href="{{ route('events.index') }}" class="brand-panel p-6 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Events</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">View Events</h3>
                    <p class="mt-2 text-sm text-slate-600">Create events, view participants, import CSV files, and track attendance.</p>
                </a>

                <a href="{{ route('events.create') }}" class="brand-panel p-6 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Creation</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Create Event</h3>
                    <p class="mt-2 text-sm text-slate-600">Set up a new corporate event and begin participant onboarding.</p>
                </a>

                <a href="{{ route('checkin.scanner') }}" class="brand-panel p-6 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Check-in</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Open QR Scanner</h3>
                    <p class="mt-2 text-sm text-slate-600">Scan participant QR codes and check attendees in on-site.</p>
                </a>
            </div>

            <div class="mt-6">
                @include('partials.tracker-leaderboard', [
                    'eyebrow' => 'Admin Visibility',
                    'title' => 'Employee Step Leaderboard',
                    'description' => 'Leaderboard based on employee tracker activity for running and walking over the last 30 days.',
                    'periodLabel' => 'Last 30 Days',
                    'rows' => $leaderboard,
                ])
            </div>

            <div class="mt-6 brand-panel p-6">
                <h3 class="text-lg font-semibold text-slate-900">Quick Start</h3>
                <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-slate-700">
                    <li>Create an event.</li>
                    <li>Upload participants via CSV on the event details page.</li>
                    <li>Use the scanner page to check participants in via QR code.</li>
                    <li>Open each event report to review attendance performance.</li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>
