<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role === 'admin') {
            $registrations = Registration::with(['user', 'event'])->latest()->get();
        } else {
            $registrations = Registration::with('event')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('registrations.index', compact('registrations'));
    }

    public function create(Event $event)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($event->status !== 'mendatang' || ! $event->is_registration_open) {
            return back()->with('error', 'Pendaftaran untuk event ini tidak tersedia.');
        }

        if ($event->is_full) {
            return back()->with('error', 'Kuota event sudah penuh.');
        }

        if (Registration::where('user_id', Auth::id())->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'Kamu sudah terdaftar pada event ini.');
        }

        return view('registrations.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($event->status !== 'mendatang' || ! $event->is_registration_open) {
            return back()->with('error', 'Pendaftaran untuk event ini tidak tersedia.');
        }

        if ($event->is_full) {
            return back()->with('error', 'Kuota event sudah penuh.');
        }

        if (Registration::where('user_id', Auth::id())->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'Kamu sudah terdaftar pada event ini.');
        }

        $rules = [];

        if ($event->type === 'tim') {
            $rules['team_name'] = 'required|string|max:255';
            $rules['substitutes'] = 'nullable|string|max:1000';
        }

        $validated = $request->validate($rules);

        Registration::create(array_merge(
            [
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'status' => 'pending',
            ],
            $validated
        ));

        return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $registration->update(['status' => $request->status]);

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function destroy(Registration $registration)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role !== 'admin' && Auth::id() !== $registration->user_id) {
            abort(403);
        }

        $registration->delete();

        return back()->with('success', 'Pendaftaran berhasil dihapus.');
    }
}
