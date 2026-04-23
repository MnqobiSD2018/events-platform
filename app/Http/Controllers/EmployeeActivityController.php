<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\TrackerLeaderboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeActivityController extends Controller
{
    public function index(TrackerLeaderboardService $leaderboardService)
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

        $connections = $user->trackerConnections()
            ->orderBy('provider')
            ->get()
            ->keyBy('provider');

        $recentImports = $user->trackerSyncImports()
            ->latest('id')
            ->limit(5)
            ->get();

        $leaderboard = $leaderboardService->leaderboard(30);
        $leaderboardPreview = $leaderboard->take(5);
        $leaderboardCurrentUser = $leaderboardService->currentUserRank($user, 30);

        return view('employee.activities.index', compact(
            'activities',
            'summary',
            'connections',
            'recentImports',
            'leaderboardPreview',
            'leaderboardCurrentUser'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        abort(403);
    }
}
