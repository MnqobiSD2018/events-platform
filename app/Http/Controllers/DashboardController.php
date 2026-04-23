<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\TrackerLeaderboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(TrackerLeaderboardService $leaderboardService)
    {
        $totalEvents = Event::where('user_id', Auth::id())->count();
        $leaderboard = $leaderboardService->leaderboard(30)->take(10);

        return view('dashboard', compact('totalEvents', 'leaderboard'));
    }
}