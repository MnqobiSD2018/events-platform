<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Event Details</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">{{ $event->name }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('checkin.scanner') }}" class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-100">Open Scanner</a>
                <a href="{{ route('events.report', $event) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">View Report</a>
                <a href="{{ route('events.edit', $event) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Edit Event</a>
            </div>
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
                <div class="brand-panel p-5 md:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Event</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">{{ $event->name }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $event->description ?: 'No description provided.' }}</p>
                    <p class="mt-3 text-sm text-slate-700"><span class="font-medium">Type:</span> {{ ucfirst($event->type) }}</p>
                    <p class="text-sm text-slate-700"><span class="font-medium">Date:</span> {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('d M Y H:i') }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Participants</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalParticipants }}</p>
                </div>
                <div class="brand-panel p-5">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Attendance Rate</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $attendanceRate }}%</p>
                </div>
            </div>

            <div class="brand-panel p-6">
                <h3 class="text-lg font-semibold text-slate-900">Import Participants (CSV)</h3>
                <p class="mt-1 text-sm text-slate-600">Expected columns: name, email, department</p>
                <form action="{{ route('events.import.csv', $event) }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:max-w-sm">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-500">Upload CSV</button>
                </form>
            </div>

            <div class="brand-panel p-6">
                <h3 class="text-lg font-semibold text-slate-900">Participants & QR Codes</h3>

                @if ($event->participants->count())
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Participant</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Department</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">QR</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($event->participants as $participant)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-slate-800">
                                            <p class="font-medium">{{ $participant->name }}</p>
                                            <p class="text-slate-500">{{ $participant->email }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">{{ $participant->department ?: '-' }}</td>
                                        <td class="px-4 py-4 text-sm">
                                            @if ($participant->attendance && $participant->attendance->checked_in_at)
                                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800">Checked in</span>
                                            @else
                                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            {!! \LaraZeus\QrCode\Facades\QrCode::size(90)->generate(\Illuminate\Support\Facades\URL::signedRoute('checkin.scan', ['participant' => $participant->id])) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-4 text-sm text-slate-600">No participants yet. Upload a CSV file to populate this event.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>