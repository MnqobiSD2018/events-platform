<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Employee Health</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Health Stats</h2>
            </div>
            <a href="{{ route('employee.home') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Back to Home</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900">
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-4">
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Last 7 Days Steps</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((int) ($summary->total_steps ?? 0)) }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Last 7 Days Runs</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((int) ($summary->total_runs ?? 0)) }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Distance (km)</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((float) ($summary->total_distance ?? 0), 2) }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Duration (min)</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((int) ($summary->total_duration ?? 0)) }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="brand-panel p-6 lg:col-span-1">
                    <h3 class="text-lg font-semibold text-slate-900">Tracker Connections</h3>
                    <p class="mt-1 text-sm text-slate-600">Health stats are sourced from connected trackers, not employee-entered values.</p>

                    <div class="mt-4 space-y-3">
                        @forelse ($connections as $provider => $connection)
                            <div class="rounded-lg border border-slate-200 bg-white p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $provider)) }}</p>
                                        <p class="text-xs text-slate-500">Tracker source</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                        {{ ucfirst($connection->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600">No trackers have been prepared yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="brand-panel p-6 lg:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Tracker Activity</h3>
                        <a href="{{ route('employee.integrations.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-500">Manage Sync</a>
                    </div>
                    @if ($activities->count())
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Steps</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Runs</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Distance</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Duration</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Source</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($activities as $activity)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $activity->activity_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst($activity->workout_type) }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format($activity->steps) }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format($activity->runs) }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format((float) $activity->distance_km, 2) }} km</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format($activity->duration_minutes) }} min</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst($activity->source) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            {{ $activities->links() }}
                        </div>
                    @else
                        <p class="mt-3 text-sm text-slate-600">No tracker activity has been imported yet.</p>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    @include('partials.tracker-leaderboard', [
                        'eyebrow' => 'Employee Leaderboard',
                        'title' => 'Top Step Movers',
                        'description' => 'Ranked by tracker activity over the last 30 days. Manual entry is not used here.',
                        'periodLabel' => 'Last 30 Days',
                        'rows' => $leaderboardPreview,
                        'highlightUserId' => Auth::id(),
                    ])
                </div>

                <div class="brand-panel p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Your Rank</h3>
                    @if ($leaderboardCurrentUser)
                        <div class="mt-4 space-y-4">
                            <div>
                                <p class="text-sm text-slate-500">Position</p>
                                <p class="mt-1 text-3xl font-semibold text-slate-900">#{{ $leaderboardCurrentUser->rank }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Total Steps</p>
                                <p class="mt-1 text-2xl font-semibold text-slate-900">{{ number_format((int) $leaderboardCurrentUser->total_steps) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Run / Walk Split</p>
                                <p class="mt-1 text-sm text-slate-700">{{ number_format((int) $leaderboardCurrentUser->run_steps) }} run steps</p>
                                <p class="text-sm text-slate-700">{{ number_format((int) $leaderboardCurrentUser->walk_steps) }} walk steps</p>
                            </div>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-600">No tracker activity in the current window yet.</p>
                    @endif
                </div>
            </div>

            <div class="brand-panel p-6">
                <h3 class="text-lg font-semibold text-slate-900">Recent Sync Imports</h3>
                @if ($recentImports->count())
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Provider</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Activities</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Synced At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($recentImports as $import)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst(str_replace('_', ' ', $import->provider)) }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst($import->status) }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ $import->imported_activities }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ $import->synced_at?->format('d M Y H:i') ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-3 text-sm text-slate-600">No sync imports recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
