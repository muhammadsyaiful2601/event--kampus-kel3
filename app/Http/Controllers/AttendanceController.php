<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Helpers\AdminActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Show the attendance management page (admin only).
     */
    public function index()
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $events = Event::latest()->get();

        return view('dashboard_admin.attendance.index', compact('events'));
    }

    /**
     * Open the attendance session for a specific event.
     */
    public function openSession(Event $event)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $event->update(['is_attendance_open' => true]);

        // Reset semua status kehadiran peserta di event ini
        $resetCount = $event->registrations()
            ->where(function ($q) {
                $q->whereNotNull('verified_at')
                  ->orWhereNotNull('attended_at');
            })
            ->update([
                'verified_at' => null,
                'verified_by' => null,
                'attended_at' => null,
                'is_late' => false,
            ]);

        AdminActivityLogger::log(
            'attendance.opened',
            'Membuka sesi absen untuk event: "' . $event->title . '" — ' . $resetCount . ' data kehadiran peserta direset.',
            'Event',
            $event->id
        );

        return back()->with('success', 'Sesi absen berhasil dibuka untuk event "' . $event->title . '". Semua status kehadiran peserta telah direset dan siap untuk scan ulang.');
    }

    /**
     * Close the attendance session for a specific event.
     */
    public function closeSession(Event $event)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $event->update(['is_attendance_open' => false]);

        AdminActivityLogger::log(
            'attendance.closed',
            'Menutup sesi absen untuk event: "' . $event->title . '"',
            'Event',
            $event->id
        );

        return back()->with('success', 'Sesi absen berhasil ditutup untuk event "' . $event->title . '". Peserta yang scan setelah ini akan ditandai terlambat.');
    }
}