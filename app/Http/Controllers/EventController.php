<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Helpers\AdminActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Public landing page
    public function index()
    {
        $ongoingEvents = Event::where('status', 'berlangsung')->latest()->get();
        $upcomingEvents = Event::where('status', 'mendatang')->orderBy('date', 'asc')->get();

        return view('landing', compact('ongoingEvents', 'upcomingEvents'));
    }

    // Participant dashboard
    public function pesertaIndex()
    {
        $userId = Auth::id();

        $ongoingEvents = Event::where('status', 'berlangsung')->latest()->get();
        $upcomingEvents = Event::where('status', 'mendatang')->orderBy('date', 'asc')->get();
        $myRegistrations = Registration::with('event')->where('user_id', $userId)->latest()->get();

        // Get registration statistics
        $pendingCount = Registration::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $acceptedCount = Registration::where('user_id', $userId)
            ->where('status', 'diterima')
            ->count();

        $rejectedCount = Registration::where('user_id', $userId)
            ->where('status', 'ditolak')
            ->count();

        return view('dashboard_peserta.index', compact('ongoingEvents', 'upcomingEvents', 'myRegistrations', 'pendingCount', 'acceptedCount', 'rejectedCount'));
    }

    // Admin event list
    public function adminIndex()
    {
        $events = Event::latest()->get();
        return view('dashboard_admin.events.index', compact('events'));
    }

    // Admin registration list
    public function adminRegistrations()
    {
        $registrations = Registration::with(['user', 'event'])->latest()->get();
        return view('dashboard_admin.registrations.index', compact('registrations'));
    }

    // Admin verify registration
    public function verifyRegistration(Request $request, Registration $registration)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $registration->update([
            'status' => $request->status,
        ]);

        if ($request->status === 'verified') {
            // Send email to participant
            \Illuminate\Support\Facades\Mail::to($registration->user->email)
                ->send(new \App\Mail\RegistrationVerifiedMail($registration));
        }

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
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

        if (Auth::user()->role !== 'peserta') {
            return back()->with('error', 'Hanya peserta yang dapat mendaftar event.');
        }

        if ($event->status !== 'mendatang') {
            return back()->with('error', 'Pendaftaran hanya diperbolehkan untuk event yang akan datang.');
        }

        if (!$event->is_registration_open) {
            return back()->with('error', 'Maaf, pendaftaran untuk event ini sudah ditutup oleh admin.');
        }

        if ($event->is_full) {
            return back()->with('error', 'Maaf, kuota event ini sudah penuh.');
        }

        // Check if already registered
        $existing = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah terdaftar di event ini.');
        }

        $registrationData = [
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'pending',
        ];

        if ($event->type === 'tim') {
            $request->validate([
                'team_name' => 'required|string|max:255',
                'substitutes' => 'nullable|string',
            ]);

            $registrationData['team_name'] = $request->team_name;
            $registrationData['substitutes'] = $request->substitutes;
        }

        Registration::create($registrationData);

        return back()->with('success', 'Berhasil mendaftar event!');
    }

    // Admin CRUD methods
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
            'location' => 'required',
            'quota' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'status' => 'required|in:berlangsung,mendatang',
            'type' => 'required|in:solo,duo,tim',
            'is_registration_open' => 'required|boolean',
        ], [
            'image.image' => 'File gambar event tidak valid.',
            'image.mimes' => 'Gambar event harus berformat JPG atau PNG.',
            'image.max' => 'Gambar event maksimal 3MB.',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        // Default: sesi absen selalu ditutup saat event baru dibuat
        $data['is_attendance_open'] = false;

        Event::create($data);

        AdminActivityLogger::log(
            'event.created',
            'Menambahkan event baru: "' . $data['title'] . '"',
            'Event'
        );

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
            'location' => 'required',
            'quota' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'status' => 'required|in:berlangsung,mendatang',
            'type' => 'required|in:solo,duo,tim',
            'is_registration_open' => 'required|boolean',
        ], [
            'image.image' => 'File gambar event tidak valid.',
            'image.mimes' => 'Gambar event harus berformat JPG atau PNG.',
            'image.max' => 'Gambar event maksimal 3MB.',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image && \Storage::disk('public')->exists($event->image)) {
                \Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        AdminActivityLogger::log(
            'event.updated',
            'Memperbarui event: "' . $event->title . '"',
            'Event',
            $event->id
        );

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $eventTitle = $event->title;
        $eventId    = $event->id;

        if ($event->image && \Storage::disk('public')->exists($event->image)) {
            \Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        AdminActivityLogger::log(
            'event.deleted',
            'Menghapus event: "' . $eventTitle . '"',
            'Event',
            $eventId
        );

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }
}
