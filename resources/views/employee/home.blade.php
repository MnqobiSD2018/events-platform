<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Employee Hub</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Welcome, {{ Auth::user()->name }}</h2>
            </div>
            <p class="text-sm text-slate-500">Track. Read. Engage.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-3">
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Unread Updates</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $unreadAnnouncements }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Total Announcements</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalAnnouncements }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">Department</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ Auth::user()->department ?: 'Not set' }}</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">7-Day Steps</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((int) ($activitySummary->total_steps ?? 0)) }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">7-Day Runs</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((int) ($activitySummary->total_runs ?? 0)) }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">7-Day Distance</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((float) ($activitySummary->total_distance ?? 0), 2) }} km</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-500">7-Day Duration</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format((int) ($activitySummary->total_duration ?? 0)) }} min</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="brand-panel p-6 lg:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Activity</h3>
                        <a href="{{ route('employee.activities.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-500">Log Activity</a>
                    </div>

                    @if ($recentActivities->count())
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Distance</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Duration</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Source</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($recentActivities as $activity)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $activity->activity_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst($activity->workout_type) }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format((float) $activity->distance_km, 2) }} km</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $activity->duration_minutes }} min</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ ucfirst($activity->source) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-600">No activity logs yet. Add your first performance entry.</p>
                    @endif

                    <div class="mt-6 flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Updates</h3>
                        <a href="{{ route('employee.announcements.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-500">View All</a>
                    </div>

                    @if ($recentAnnouncements->count())
                        <div class="mt-4 space-y-3">
                            @foreach ($recentAnnouncements as $announcement)
                                @php $isRead = in_array($announcement->id, $readAnnouncementIds, true); @endphp
                                <div class="rounded-lg border border-slate-200 bg-white p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $announcement->title }}</p>
                                            <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">{{ ucfirst($announcement->category) }}</p>
                                        </div>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $isRead ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $isRead ? 'Read' : 'Unread' }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-700">{{ \Illuminate\Support\Str::limit($announcement->body, 140) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-600">No updates yet. Check back later for company and HR announcements.</p>
                    @endif
                </div>

                <div class="brand-panel p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('employee.activities.index') }}" class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-800 hover:bg-slate-50">
                            Log Performance
                        </a>
                        <a href="{{ route('employee.integrations.index') }}" class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-800 hover:bg-slate-50">
                            Tracker Integrations
                        </a>
                        <a href="{{ route('employee.announcements.index') }}" class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-800 hover:bg-slate-50">
                            Browse Announcements
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-800 hover:bg-slate-50">
                            Update Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
