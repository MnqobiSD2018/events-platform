<?php

namespace App\Http\Controllers;
use App\Models\Participant;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function scanner()
    {
        return view('checkin.scanner');
    }

    public function scan(Participant $participant)
    {
        $employeeUser = User::firstOrCreate(
            ['email' => $participant->email],
            [
                'name' => $participant->name,
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
                'user_type' => User::TYPE_EMPLOYEE,
                'team' => 'Wellness Squad',
                'department' => $participant->department,
                'employee_role' => 'participant',
                'privacy_settings' => [
                    'share_with_colleagues' => true,
                    'show_department' => true,
                ],
            ]
        );

        $accountCreated = $employeeUser->wasRecentlyCreated;

        // Check if already checked in
        $attendance = Attendance::firstOrNew([
            'participant_id' => $participant->id,
            'event_id' => $participant->event_id,
        ]);

        if (! $attendance->checked_in_at) {
            $attendance->checked_in_at = now();
            $attendance->save();

            $payload = [
                'success' => true,
                'message' => 'Checked in successfully!',
                'participant' => $participant,
                'accountCreated' => $accountCreated,
                'hasEmployeeAccount' => true,
            ];

            if (request()->expectsJson()) {
                return response()->json($payload);
            }

            if (Auth::check() && Auth::user()->email === $participant->email) {
                return redirect()->route('home')->with('success', $payload['message']);
            }

            return view('checkin.result', $payload);
        }

        $payload = [
            'success' => false,
            'message' => 'Already checked in.',
            'participant' => $participant,
            'accountCreated' => $accountCreated,
            'hasEmployeeAccount' => true,
        ];

        if (request()->expectsJson()) {
            return response()->json($payload);
        }

        if (Auth::check() && Auth::user()->email === $participant->email) {
            return redirect()->route('home')->with('success', $payload['message']);
        }

        return view('checkin.result', $payload);
    }
}
