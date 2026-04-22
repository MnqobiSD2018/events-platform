<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Reporting</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Attendance Report: {{ $event->name }}</h2>
            </div>
            <a href="{{ route('events.show', $event) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Back to Event</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-600">Total Participants</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalParticipants }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-600">Checked In</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $attendees }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-sm text-slate-600">Attendance Rate</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $attendanceRate }}%</p>
                </div>
            </div>

            <div class="brand-panel overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Department</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Checked In</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($event->participants as $participant)
                            <tr>
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $participant->name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $participant->email }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $participant->department ?: '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($participant->attendance && $participant->attendance->checked_in_at)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800">
                                            {{ $participant->attendance->checked_in_at->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800">Not checked in</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>