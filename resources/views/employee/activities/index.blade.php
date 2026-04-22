<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Performance</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Activity Logging</h2>
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
                    <h3 class="text-lg font-semibold text-slate-900">Log New Activity</h3>
                    <form action="{{ route('employee.activities.store') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="activity_date" class="block text-sm font-medium text-slate-700">Date</label>
                            <input id="activity_date" name="activity_date" type="date" value="{{ old('activity_date', now()->toDateString()) }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        </div>
                        <div>
                            <label for="workout_type" class="block text-sm font-medium text-slate-700">Workout Type</label>
                            <select id="workout_type" name="workout_type" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                @foreach (['run', 'walk', 'cycle', 'gym', 'yoga', 'other'] as $type)
                                    <option value="{{ $type }}" @selected(old('workout_type') === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="steps" class="block text-sm font-medium text-slate-700">Steps</label>
                                <input id="steps" name="steps" type="number" min="0" value="{{ old('steps', 0) }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                            <div>
                                <label for="runs" class="block text-sm font-medium text-slate-700">Runs</label>
                                <input id="runs" name="runs" type="number" min="0" value="{{ old('runs', 0) }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                            <div>
                                <label for="distance_km" class="block text-sm font-medium text-slate-700">Distance (km)</label>
                                <input id="distance_km" name="distance_km" type="number" step="0.01" min="0" value="{{ old('distance_km', 0) }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-slate-700">Duration (min)</label>
                                <input id="duration_minutes" name="duration_minutes" type="number" min="0" value="{{ old('duration_minutes', 0) }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700">Notes (optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Save Activity</button>
                    </form>
                </div>

                <div class="brand-panel p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-900">Recent Activity</h3>
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
                        <p class="mt-3 text-sm text-slate-600">No activity logs yet. Use the form to add your first entry.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
