<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\TrackerLeaderboardService;
use Illuminate\Support\Facades\Auth;

class EmployeeHomeController extends Controller
{
    public function index(TrackerLeaderboardService $leaderboardService)
    {
        $user = Auth::user();

        $totalNotifications = $user->notifications()->count();
        $unreadNotifications = $user->unreadNotifications()->count();

        $recentNotifications = $user->notifications()
            ->latest('created_at')
            ->limit(5)
            ->get();

        $activitySummary = ActivityLog::query()
            ->where('user_id', $user->id)
            ->whereDate('activity_date', '>=', now()->subDays(6)->toDateString())
            ->selectRaw('COALESCE(SUM(steps), 0) as total_steps')
            ->selectRaw('COALESCE(SUM(runs), 0) as total_runs')
            ->selectRaw('COALESCE(SUM(distance_km), 0) as total_distance')
            ->selectRaw('COALESCE(SUM(duration_minutes), 0) as total_duration')
            ->first();

        $recentActivities = ActivityLog::query()
            ->where('user_id', $user->id)
            ->latest('activity_date')
            ->latest('id')
            ->limit(5)
            ->get();

        $leaderboard = $leaderboardService->leaderboard(30);
        $leaderboardPreview = $leaderboard->take(5);
        $leaderboardCurrentUser = $leaderboardService->currentUserRank($user, 30);

        return view('employee.home', compact(
            'totalNotifications',
            'unreadNotifications',
            'recentNotifications',
            'activitySummary',
            'recentActivities',
            'leaderboardPreview',
            'leaderboardCurrentUser'
        ));
    }
}
