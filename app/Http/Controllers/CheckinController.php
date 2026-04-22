<?php

namespace App\Http\Controllers;
use App\Models\Participant;
use App\Models\Attendance;

use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function scanner()
    {
        return view('checkin.scanner');
    }

    public function scan(Participant $participant)
    {
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
            ];

            if (request()->expectsJson()) {
                return response()->json($payload);
            }

            return view('checkin.result', $payload);
        }

        $payload = [
            'success' => false,
            'message' => 'Already checked in.',
            'participant' => $participant,
        ];

        if (request()->expectsJson()) {
            return response()->json($payload);
        }

        return view('checkin.result', $payload);
    }
}
