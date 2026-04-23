<div class="brand-panel p-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="brand-kicker">{{ $eyebrow ?? 'Leaderboard' }}</p>
            <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $title ?? 'Top Employee Steps' }}</h3>
            <p class="mt-2 text-sm text-slate-600">{{ $description ?? 'Ranked by total steps from run and walk tracker activity in the last 30 days.' }}</p>
        </div>
        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $periodLabel ?? 'Last 30 Days' }}</span>
    </div>

    @if ($rows->count())
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Steps</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Run Steps</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Walk Steps</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Distance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($rows as $row)
                        <tr class="{{ isset($highlightUserId) && (int) $highlightUserId === (int) $row->user_id ? 'bg-amber-50/70' : '' }}">
                            <td class="px-4 py-3 text-sm font-semibold text-slate-700">#{{ $row->rank }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                <div class="font-medium text-slate-900">{{ $row->name }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $row->department ?: 'Department not set' }}
                                    @if (! empty($row->team))
                                        · {{ $row->team }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format((int) $row->total_steps) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format((int) $row->run_steps) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format((int) $row->walk_steps) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ number_format((float) $row->total_distance, 2) }} km</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-4 text-sm text-slate-600">No tracker activity has been imported yet.</p>
    @endif
</div>
