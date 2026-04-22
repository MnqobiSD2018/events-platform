<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activities = $user->activityLogs()
            ->latest('activity_date')
            ->latest('id')
            ->paginate(12);

        $summary = $user->activityLogs()
            ->whereDate('activity_date', '>=', now()->subDays(6)->toDateString())
            ->selectRaw('COALESCE(SUM(steps), 0) as total_steps')
            ->selectRaw('COALESCE(SUM(runs), 0) as total_runs')
            ->selectRaw('COALESCE(SUM(distance_km), 0) as total_distance')
            ->selectRaw('COALESCE(SUM(duration_minutes), 0) as total_duration')
            ->first();

        return view('employee.activities.index', compact('activities', 'summary'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'activity_date' => ['required', 'date'],
            'workout_type' => ['required', 'string', 'in:run,walk,cycle,gym,yoga,other'],
            'steps' => ['nullable', 'integer', 'min:0'],
            'runs' => ['nullable', 'integer', 'min:0'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $hasMetric =
            (int) ($validated['steps'] ?? 0) > 0 ||
            (int) ($validated['runs'] ?? 0) > 0 ||
            (float) ($validated['distance_km'] ?? 0) > 0 ||
            (int) ($validated['duration_minutes'] ?? 0) > 0;

        if (! $hasMetric) {
            return back()->withErrors([
                'metrics' => 'Provide at least one metric: steps, runs, distance, or duration.',
            ])->withInput();
        }

        Auth::user()->activityLogs()->create([
            ...$validated,
            'source' => 'manual',
            'provider' => null,
            'raw_payload' => null,
        ]);

        return redirect()->route('employee.activities.index')->with('success', 'Activity logged successfully.');
    }
}
