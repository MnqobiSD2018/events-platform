<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Collection;

class TrackerLeaderboardService
{
    public function leaderboard(int $days = 30): Collection
    {
        $since = now()->subDays(max($days - 1, 0))->toDateString();

        return ActivityLog::query()
            ->join('users', 'users.id', '=', 'activity_logs.user_id')
            ->where('users.user_type', User::TYPE_EMPLOYEE)
            ->whereDate('activity_logs.activity_date', '>=', $since)
            ->whereIn('activity_logs.workout_type', ['run', 'walk'])
            ->select([
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.team',
                'users.department',
                'users.employee_role',
            ])
            ->selectRaw("COALESCE(SUM(CASE WHEN activity_logs.workout_type = 'run' THEN activity_logs.steps ELSE 0 END), 0) as run_steps")
            ->selectRaw("COALESCE(SUM(CASE WHEN activity_logs.workout_type = 'walk' THEN activity_logs.steps ELSE 0 END), 0) as walk_steps")
            ->selectRaw('COALESCE(SUM(activity_logs.steps), 0) as total_steps')
            ->selectRaw('COALESCE(SUM(activity_logs.runs), 0) as total_runs')
            ->selectRaw('COALESCE(SUM(activity_logs.distance_km), 0) as total_distance')
            ->selectRaw('COUNT(activity_logs.id) as activity_count')
            ->selectRaw('MAX(activity_logs.activity_date) as last_activity_date')
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.team',
                'users.department',
                'users.employee_role',
            )
            ->orderByDesc('total_steps')
            ->orderByDesc('total_distance')
            ->orderBy('users.name')
            ->get()
            ->values()
            ->map(function ($row, int $index) {
                $row->rank = $index + 1;

                return $row;
            });
    }

    public function currentUserRank(User $user, int $days = 30): ?object
    {
        return $this->leaderboard($days)->firstWhere('user_id', $user->id);
    }
}
