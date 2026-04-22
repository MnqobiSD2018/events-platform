<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class EmployeeHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalAnnouncements = Announcement::query()->count();
        $unreadAnnouncements = Announcement::query()
            ->whereDoesntHave('reads', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

        $recentAnnouncements = Announcement::query()
            ->latest('published_at')
            ->latest('id')
            ->limit(5)
            ->get();

        $readAnnouncementIds = $user->announcementReads()
            ->pluck('announcement_id')
            ->all();

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

        return view('employee.home', compact(
            'totalAnnouncements',
            'unreadAnnouncements',
            'recentAnnouncements',
            'readAnnouncementIds',
            'activitySummary',
            'recentActivities'
        ));
    }
}
