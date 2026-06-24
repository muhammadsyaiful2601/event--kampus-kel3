<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Common\ErrorCorrectionLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Display a listing of all registrations with search and filter.
     */
    public function index(Request $request)
    {
        $query = Registration::with(['user', 'event']);

        // Otorisasi Utama: Batasi data jika bukan admin
        if (strtolower(Auth::user()->role) !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Perbaikan Bug Search: Dibungkus dalam closure agar tidak merusak filter user_id
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('event', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan status pendaftaran
        if ($request->filled('status') && $request->input('status') !== 'semua') {
            $query->where('status', $request->input('status'));
        }

        $registrations = $query->latest()->paginate(10);
        $stats = $this->getStatistics();

        return view('registrations.index', compact('registrations', 'stats'));
    }

    /**
     * Get registration statistics.
     */
    private function getStatistics()
    {
        $baseQuery = Registration::query();

        if (strtolower(Auth::user()->role) !== 'admin') {
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
            'participant_name' => 'required|string|max:255',
            'team_name' => 'nullable|string|max:255',
            'department' => 'required|string|max:255',
            'year' => 'required|string|max:100',
            'age' => 'required|integer|min:10|max:120',
            'participant_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Cegah pendaftaran ganda pada event yang sama
        $existing = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah terdaftar pada event ini.');
        }

        // Validasi ketersediaan kuota
        if ($event->is_full) {
            return back()->with('error', 'Kuota event sudah penuh.');
        }

        $photoPath = $request->file('participant_photo')->store('participant_photos', 'public');

        // Generate tiket unik berwujud string random acak
        $ticketCode = strtoupper(Str::random(10));
        while (Registration::where('ticket_code', $ticketCode)->exists()) {
            $ticketCode = strtoupper(Str::random(10));
        }

        Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'participant_name' => $request->participant_name,
            'team_name' => $request->team_name,
            'department' => $request->department,
            'year' => $request->year,
            'age' => $request->age,
            'participant_photo' => $photoPath,
            'ticket_code' => $ticketCode,
            'status' => 'pending',
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    /**
     * Show the specified registration details.
     */
    public function show(Registration $pendaftaran)
    {
        // FIX UTAMA: Operator '!=' mengatasi perbedaan tipe data Auth::id() [int] dengan user_id [string]
        if (strtolower(Auth::user()->role) !== 'admin' && Auth::id() != $pendaftaran->user_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat tiket ini.');
        }

        $pendaftaran->load(['user', 'event']);

        $qrCode = null;
        if ($pendaftaran->status === 'diterima' && $pendaftaran->ticket_code) {
            $pngData = $this->generateQrPng($pendaftaran->ticket_code, 4);
            $qrCode = base64_encode($pngData);
        }

        return view('registrations.show', [
            'registration' => $pendaftaran,
            'qrCode' => $qrCode,
            'qrFormat' => 'png',
        ]);
    }

    /**
     * Show the form for editing the registration status.
     */
    public function edit(Registration $pendaftaran)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
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
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $currentStatus = $pendaftaran->status;
        $newStatus = $request->input('status');

        $allowedTransitions = [
            'pending' => ['diterima', 'ditolak', 'pending'],
            'diterima' => ['pending', 'diterima'],
            'ditolak' => ['pending', 'ditolak'],
        ];

        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Transisi status tidak diizinkan.');
        }

        $pendaftaran->update(['status' => $newStatus]);

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil diperbarui.');
    }

    /**
     * Update the registration status via PATCH method.
     */
    public function updateStatus(Request $request, Registration $pendaftaran)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $currentStatus = $pendaftaran->status;
        $newStatus = $request->input('status');

        $allowedTransitions = [
            'pending' => ['diterima', 'ditolak', 'pending'],
            'diterima' => ['pending', 'diterima'],
            'ditolak' => ['pending', 'ditolak'],
        ];

        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Transisi status tidak diizinkan.');
        }

        $pendaftaran->update(['status' => $newStatus]);

        return redirect()->route('pendaftaran.index')->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    /**
     * Show the QR scanner page for admin verification.
     */
    public function scan()
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $recentScans = Registration::with(['event', 'user'])
            ->whereNotNull('verified_at')
            ->orderBy('verified_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard_admin.registrations.scan', compact('recentScans'));
    }

    /**
     * Verify a scanned ticket code from admin scanner.
     */
    public function verifyScan(Request $request)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $registration = Registration::with(['event', 'user'])->where('ticket_code', $code)->first();

        if (!$registration) {
            return response()->json(['status' => 'error', 'message' => 'Kode QR atau tiket tidak valid.'], 404);
        }

        $responseData = [
            'name' => $registration->participant_name ?? $registration->user->name,
            'event' => $registration->event->title,
            'ticket_code' => $registration->ticket_code,
            'department' => $registration->department ?? '-',
            'team_name' => $registration->team_name ?? '-',
            'status' => $registration->status,
        ];

        if ($registration->status === 'ditolak') {
            return response()->json([
                'status' => 'error',
                'message' => 'Pendaftaran ini telah DITOLAK. Peserta tidak diizinkan masuk.',
                'data' => $responseData
            ], 200);
        }

        if ($registration->verified_at !== null) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Peserta sudah diverifikasi sebelumnya pada ' . $registration->verified_at->format('d M Y H:i'),
                'data' => $responseData
            ]);
        }

        $updateData = [
            'verified_at' => now(),
            'verified_by' => Auth::user()->name,
        ];

        if ($registration->status === 'pending') {
            $updateData['status'] = 'diterima';
            $responseData['status'] = 'diterima';
        }

        $registration->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi Berhasil! Silakan dipersilakan masuk.',
            'data' => $responseData,
        ]);
    }

    /**
     * Remove the specified registration from storage.
     */
    public function destroy(Registration $pendaftaran)
    {
        if (strtolower(Auth::user()->role) !== 'admin' && Auth::id() != $pendaftaran->user_id) {
            abort(403);
        }

        $pendaftaran->delete();
        return back()->with('success', 'Pendaftaran berhasil dihapus.');
    }

    /**
     * Download QR Code for registration ticket as PNG (4x scale).
     */
    public function downloadQr(Registration $pendaftaran)
    {
        if (strtolower(Auth::user()->role) !== 'admin' && Auth::id() != $pendaftaran->user_id) {
            abort(403);
        }

        if ($pendaftaran->status !== 'diterima') {
            return back()->with('error', 'QR Code hanya tersedia untuk pendaftaran yang diterima.');
        }

        $pngData = $this->generateQrPng($pendaftaran->ticket_code, 4);
        $filename = 'QR_' . $pendaftaran->ticket_code . '.png';

        return response($pngData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Download Certificate/Document for participant (HTML with embedded QR PNG).
     */
    public function downloadCertificate(Registration $pendaftaran)
    {
        if (strtolower(Auth::user()->role) !== 'admin' && Auth::id() != $pendaftaran->user_id) {
            abort(403);
        }

        if ($pendaftaran->status !== 'diterima') {
            return back()->with('error', 'Dokumen hanya tersedia untuk pendaftaran yang diterima.');
        }

        $qrPngBase64 = base64_encode($this->generateQrPng($pendaftaran->ticket_code, 4));
        $html = $this->generateCertificateHtml($pendaftaran, $qrPngBase64);

        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="dokumen_peserta_' . $pendaftaran->ticket_code . '.html"');
    }

    /**
     * Generate a PNG image of the QR code using GD extension.
     */
    private function generateQrPng(string $content, int $scale = 4): string
    {
        $qrCode = Encoder::encode($content, ErrorCorrectionLevel::M());
        $matrix  = $qrCode->getMatrix();
        $matrixSize = $matrix->getWidth();

        $margin = 4;
        $totalModules = $matrixSize + $margin * 2;
        $imageSize    = $totalModules * $scale;

        $image = imagecreatetruecolor($imageSize, $imageSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $white);

        for ($y = 0; $y < $matrixSize; $y++) {
            for ($x = 0; $x < $matrixSize; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    $px = ($x + $margin) * $scale;
                    $py = ($y + $margin) * $scale;
                    imagefilledrectangle($image, $px, $py, $px + $scale - 1, $py + $scale - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();
        imagedestroy($image);

        return $pngData;
    }

    /**
     * Generate HTML document for participant certificate.
     */
    private function generateCertificateHtml(Registration $pendaftaran, string $qrPngBase64 = '')
    {
        $pendaftaran->load(['user', 'event']);

        $name = $pendaftaran->participant_name;
        $email = $pendaftaran->user->email;
        $department = $pendaftaran->department ?? '-';
        $year = $pendaftaran->year ?? '-';
        $age = $pendaftaran->age ?? '-';
        $teamName = $pendaftaran->team_name ?? '-';
        $eventTitle = $pendaftaran->event->title;
        $eventDate = $pendaftaran->event->date;
        $eventLocation = $pendaftaran->event->location;
        $ticketCode = $pendaftaran->ticket_code;
        $registrationDate = $pendaftaran->created_at->format('d M Y H:i');
        $currentDate = now()->format('d M Y H:i');

        $qrImageTag = $qrPngBase64
            ? '<img src="data:image/png;base64,' . $qrPngBase64 . '" alt="QR Code ' . $ticketCode . '" style="width:152px;height:152px;image-rendering:pixelated;">'
            : '<p style="color:#999;">(QR Code tidak tersedia)</p>';

        return <<<HTML
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dokumen Peserta - {$name}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Segoe UI', sans-serif; background-color: #f5f5f5; padding: 20px; }
                .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
                .header h1 { color: #007bff; font-size: 28px; margin-bottom: 10px; }
                .header p { color: #666; font-size: 14px; }
                .section { margin-bottom: 30px; }
                .section-title { background-color: #f8f9fa; padding: 12px 15px; border-left: 4px solid #007bff; font-weight: bold; margin-bottom: 15px; font-size: 16px; color: #333; }
                .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px; }
                .row.full { grid-template-columns: 1fr; }
                .field { display: flex; flex-direction: column; }
                .field-label { font-weight: 600; color: #555; margin-bottom: 5px; font-size: 13px; text-transform: uppercase; }
                .field-value { color: #333; font-size: 16px; padding: 8px; background-color: #fafafa; border: 1px solid #ddd; border-radius: 4px; }
                .qr-section { text-align: center; background-color: #f8f9fa; padding: 30px; border-radius: 8px; margin-top: 30px; }
                .qr-section p { font-size: 14px; color: #666; margin-top: 10px; }
                .ticket-code { font-family: 'Courier New', monospace; font-size: 14px; color: #007bff; font-weight: bold; margin-top: 10px; }
                .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 12px; }
                .status { display: inline-block; padding: 6px 12px; border-radius: 4px; font-weight: bold; font-size: 14px; }
                .status-diterima { background-color: #d4edda; color: #155724; }
                @media print { body { background-color: white; padding: 0; } .container { box-shadow: none; } }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>📋 Dokumen Peserta</h1>
                    <p>Verifikasi Pendaftaran Event</p>
                </div>
                <div class="section">
                    <div class="section-title">Informasi Peserta</div>
                    <div class="row">
                        <div class="field"><label class="field-label">Nama Lengkap</label><div class="field-value">{$name}</div></div>
                        <div class="field"><label class="field-label">Email</label><div class="field-value">{$email}</div></div>
                    </div>
                    <div class="row">
                        <div class="field"><label class="field-label">Jurusan</label><div class="field-value">{$department}</div></div>
                        <div class="field"><label class="field-label">Angkatan</label><div class="field-value">{$year}</div></div>
                    </div>
                    <div class="row">
                        <div class="field"><label class="field-label">Usia</label><div class="field-value">{$age} tahun</div></div>
                        <div class="field"><label class="field-label">Nama Tim</label><div class="field-value">{$teamName}</div></div>
                    </div>
                </div>
                <div class="section">
                    <div class="section-title">Informasi Event</div>
                    <div class="row full">
                        <div class="field"><label class="field-label">Nama Event</label><div class="field-value">{$eventTitle}</div></div>
                    </div>
                    <div class="row">
                        <div class="field"><label class="field-label">Tanggal Event</label><div class="field-value">{$eventDate}</div></div>
                        <div class="field"><label class="field-label">Lokasi</label><div class="field-value">{$eventLocation}</div></div>
                    </div>
                </div>
                <div class="section">
                    <div class="section-title">Status Pendaftaran</div>
                    <div class="row full">
                        <div class="field"><label class="field-label">Status</label><div class="field-value"><span class="status status-diterima">✓ DITERIMA</span></div></div>
                    </div>
                    <div class="row">
                        <div class="field"><label class="field-label">Kode Tiket</label><div class="field-value">{$ticketCode}</div></div>
                        <div class="field"><label class="field-label">Tanggal Pendaftaran</label><div class="field-value">{$registrationDate}</div></div>
                    </div>
                </div>
                <div class="qr-section">
                    <p><strong>Tunjukkan QR code di bawah kepada admin di lapangan untuk verifikasi kehadiran.</strong></p>
                    <br>
                    {$qrImageTag}
                    <p class="ticket-code">Kode Tiket: {$ticketCode}</p>
                </div>
                <div class="footer">
                    <p>Dokumen ini dicetak pada: {$currentDate}</p>
                    <p>Harap simpan dokumen ini sampai selesainya event.</p>
                </div>
            </div>
        </body>
        </html>
        HTML;
    }
}
