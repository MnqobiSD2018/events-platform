<?php

namespace App\Http\Controllers;

use App\Models\BroadcastNotification;
use App\Models\User;
use App\Notifications\HrBroadcastNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $broadcasts = BroadcastNotification::query()
            ->with('sender:id,name,email')
            ->latest()
            ->paginate(12);

        return view('admin.notifications.index', compact('broadcasts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:1000'],
            'category' => ['required', 'string', 'in:hr,wellness,policy,general'],
        ]);

        $employees = User::query()
            ->where('user_type', User::TYPE_EMPLOYEE)
            ->get(['id', 'name', 'email']);

        $broadcast = BroadcastNotification::create([
            ...$validated,
            'sent_by' => Auth::id(),
            'recipient_count' => $employees->count(),
        ]);

        if ($employees->isNotEmpty()) {
            Notification::send($employees, new HrBroadcastNotification($broadcast));
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Notification sent to '.$employees->count().' employee(s).');
    }
}
