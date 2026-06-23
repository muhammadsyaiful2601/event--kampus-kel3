<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Display a listing of all registrations with search and filter.
     */
    public function index(Request $request)
    {
        $query = Registration::with(['user', 'event']);

        // Authorization check
        if (Auth::user()->role !== 'admin') {
            $query = $query->where('user_id', Auth::id());
        }

        // Search by participant name, email, or event name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query = $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhereHas('event', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'semua') {
            $query = $query->where('status', $request->input('status'));
        }

        // Order by latest
        $registrations = $query->latest()->paginate(10);

        // Get statistics
        $stats = $this->getStatistics();

        return view('registrations.index', compact('registrations', 'stats'));
    }

    /**
     * Get registration statistics.
     */
    private function getStatistics()
    {
        $baseQuery = Registration::query();

        if (Auth::user()->role !== 'admin') {
            $baseQuery->where('user_id', Auth::id());
        }

        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'diterima' => (clone $baseQuery)->where('status', 'diterima')->count(),
            'ditolak' => (clone $baseQuery)->where('status', 'ditolak')->count(),
        ];
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        $events = Event::where('status', 'mendatang')
            ->where('is_registration_open', true)
            ->get();

        return view('registrations.create', compact('events'));
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Check if user already registered
        $existing = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah terdaftar pada event ini.');
        }

        // Check if event is full
        if ($event->is_full) {
            return back()->with('error', 'Kuota event sudah penuh.');
        }

        Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    /**
     * Show the specified registration details.
     */
    public function show(Registration $pendaftaran)
    {
        // Authorization
        if (Auth::user()->role !== 'admin' && Auth::id() !== $pendaftaran->user_id) {
            abort(403);
        }

        $pendaftaran->load(['user', 'event']);

        return view('registrations.show', ['registration' => $pendaftaran]);
    }

    /**
     * Show the form for editing the registration status.
     */
    public function edit(Registration $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $pendaftaran->load(['user', 'event']);

        return view('registrations.edit', ['registration' => $pendaftaran]);
    }

    /**
     * Update the registration in storage (PUT method).
     */
    public function update(Request $request, Registration $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        // Validate status transitions
        $currentStatus = $pendaftaran->status;
        $newStatus = $request->input('status');
        
        // Allow status transitions
        $allowedTransitions = [
            'pending' => ['diterima', 'ditolak', 'pending'],
            'diterima' => ['pending', 'diterima'],
            'ditolak' => ['pending', 'ditolak'],
        ];

        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Transisi status tidak diizinkan.');
        }

        $pendaftaran->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil diperbarui.');
    }

    /**
     * Update the registration status via PATCH method.
     */
    public function updateStatus(Request $request, Registration $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        // Validate status transitions
        $currentStatus = $pendaftaran->status;
        $newStatus = $request->input('status');
        
        // Allow status transitions
        $allowedTransitions = [
            'pending' => ['diterima', 'ditolak', 'pending'],
            'diterima' => ['pending', 'diterima'],
            'ditolak' => ['pending', 'ditolak'],
        ];

        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Transisi status tidak diizinkan.');
        }

        $pendaftaran->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified registration from storage.
     */
    public function destroy(Registration $pendaftaran)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $pendaftaran->user_id) {
            abort(403);
        }

        $pendaftaran->delete();

        return back()->with('success', 'Pendaftaran berhasil dihapus.');
    }
}

