<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Public landing page
    public function index()
    {
        $events = Event::latest()->get();
        return view('landing', compact('events'));
    }

    // Admin event list
    public function adminIndex()
    {
        $events = Event::latest()->get();
        return view('dashboard_admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    // Event registration logic
    public function register(Request $request, Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mendaftar event.');
        }

        // Check if already registered
        $existing = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah terdaftar di event ini.');
        }

        Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Berhasil mendaftar event!');
    }

    // Admin CRUD methods (Simplified for now)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
            'location' => 'required',
            'quota' => 'nullable|integer',
        ]);

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }
}
