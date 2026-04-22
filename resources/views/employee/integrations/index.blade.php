<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Integrations</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Wearable Sync Points</h2>
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

            <div class="brand-panel p-6">
                <h3 class="text-lg font-semibold text-slate-900">Provider Connectors</h3>
                <p class="mt-1 text-sm text-slate-600">These are the planned sync points for future OAuth and device sync flows.</p>
                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($providers as $provider)
                        @php
                            $connection = $connections->get($provider);
                            $label = ucfirst(str_replace('_', ' ', $provider));
                        @endphp
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-sm font-semibold text-slate-900">{{ $label }}</h4>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                    {{ $connection?->status ? ucfirst($connection->status) : 'Not configured' }}
                                </span>
                            </div>
                            <form action="{{ route('employee.integrations.connect', $provider) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                    Prepare Sync Point
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="brand-panel p-6">
                <h3 class="text-lg font-semibold text-slate-900">Manual Import Hook (MVP)</h3>
                <p class="mt-1 text-sm text-slate-600">Use this to simulate external tracker payload ingestion while OAuth/device callbacks are being built.</p>
                <form action="{{ route('employee.integrations.import') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="provider" class="block text-sm font-medium text-slate-700">Provider</label>
                            <select id="provider" name="provider" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider }}">{{ ucfirst(str_replace('_', ' ', $provider)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="external_reference" class="block text-sm font-medium text-slate-700">External Reference (optional)</label>
                            <input id="external_reference" name="external_reference" type="text" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="device-log-id-123">
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-slate-700">Payload fields</p>
                        <p class="mt-1 text-xs text-slate-500">Send workout_type, steps, runs, distance_km, duration_minutes, activity_date, notes.</p>
                    </div>

                    <input type="hidden" name="payload[activity_date]" value="{{ now()->toDateString() }}">
                    <input type="hidden" name="payload[workout_type]" value="run">
                    <input type="hidden" name="payload[steps]" value="5000">
                    <input type="hidden" name="payload[runs]" value="1">
                    <input type="hidden" name="payload[distance_km]" value="5.2">
                    <input type="hidden" name="payload[duration_minutes]" value="34">
                    <input type="hidden" name="payload[notes]" value="Imported from simulated provider payload">

                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
                        Submit Simulated Import
                    </button>
                </form>
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
