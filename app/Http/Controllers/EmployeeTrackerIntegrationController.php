<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeTrackerIntegrationController extends Controller
{
    private const SUPPORTED_PROVIDERS = [
        'apple_health',
        'google_fit',
        'garmin',
        'fitbit',
        'strava',
    ];

    public function index()
    {
        $connections = Auth::user()->trackerConnections()
            ->orderBy('provider')
            ->get()
            ->keyBy('provider');

        $recentImports = Auth::user()->trackerSyncImports()
            ->latest('id')
            ->limit(10)
            ->get();

        return view('employee.integrations.index', [
            'providers' => self::SUPPORTED_PROVIDERS,
            'connections' => $connections,
            'recentImports' => $recentImports,
        ]);
    }

    public function connect(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            abort(404);
        }

        Auth::user()->trackerConnections()->updateOrCreate(
            ['provider' => $provider],
            [
                'status' => 'planned',
                'metadata' => [
                    'note' => 'Connection scaffold created. OAuth sync can be added next.',
                ],
            ]
        );

        return back()->with('success', ucfirst(str_replace('_', ' ', $provider)).' connection point is prepared.');
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => ['required', 'string', 'in:apple_health,google_fit,garmin,fitbit,strava'],
            'payload' => ['required', 'array'],
            'external_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = $validated['payload'];

        $activity = [
            'activity_date' => data_get($payload, 'activity_date', now()->toDateString()),
            'workout_type' => data_get($payload, 'workout_type', 'other'),
            'steps' => (int) data_get($payload, 'steps', 0),
            'runs' => (int) data_get($payload, 'runs', 0),
            'distance_km' => (float) data_get($payload, 'distance_km', 0),
            'duration_minutes' => (int) data_get($payload, 'duration_minutes', 0),
            'notes' => data_get($payload, 'notes'),
        ];

        $importedCount = 0;

        if (in_array($activity['workout_type'], ['run', 'walk', 'cycle', 'gym', 'yoga', 'other'], true)) {
            Auth::user()->activityLogs()->create([
                ...$activity,
                'source' => 'imported',
                'provider' => $validated['provider'],
                'raw_payload' => $payload,
            ]);
            $importedCount = 1;
        }

        Auth::user()->trackerSyncImports()->create([
            'provider' => $validated['provider'],
            'external_reference' => $validated['external_reference'] ?? null,
            'status' => 'completed',
            'payload' => $payload,
            'imported_activities' => $importedCount,
            'synced_at' => now(),
            'error_message' => null,
        ]);

        return back()->with('success', 'Tracker payload received and stored for sync planning.');
    }
}
