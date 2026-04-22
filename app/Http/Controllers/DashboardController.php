<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::where('user_id', Auth::id())->count();

        return view('dashboard', compact('totalEvents'));
    }
}