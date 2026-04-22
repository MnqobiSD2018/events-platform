<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Event::class);

        $events = Event::where('user_id', Auth::id())->latest()->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Event::class);

        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'event_date' => 'required|date',
        ]);
    
        $validated['user_id'] = Auth::id();
        Event::create($validated);
        
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $event->load(['participants.attendance']);
    
        $totalParticipants = $event->participants()->count();
        $attendees = Attendance::where('event_id', $event->id)
                            ->whereNotNull('checked_in_at')
                            ->count();
        $attendanceRate = $totalParticipants > 0 ? round(($attendees / $totalParticipants) * 100, 2) : 0;
        
        return view('events.show', compact('event', 'totalParticipants', 'attendees', 'attendanceRate'));
    }

    public function importCsv(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);
        
        Excel::import(new ParticipantsImport($event->id), $request->file('csv_file'));
        
        return redirect()->route('events.show', $event)
        ->with('success', 'Participants imported successfully.');
    }

    public function report(Event $event)
    {
        $this->authorize('view', $event);

        $event->load(['participants.attendance']);
        
        $totalParticipants = $event->participants()->count();
        $attendees = Attendance::where('event_id', $event->id)
                            ->whereNotNull('checked_in_at')
                            ->count();
        $attendanceRate = $totalParticipants > 0 ? round(($attendees / $totalParticipants) * 100, 2) : 0;
        
        return view('events.report', compact('event', 'totalParticipants', 'attendees', 'attendanceRate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.create', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'event_date' => 'required|date',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
