<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-compact"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

@include('components.header')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('components.sidebar')
            <div class="layout-page">
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Header Section -->
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h4 class="fw-bold py-3 mb-2">Detail Pendaftaran Peserta</h4>
                                <p class="text-muted mb-0">Informasi lengkap pendaftaran event dan peserta.</p>
                            </div>
                            <a href="{{ route('pendaftaran.index') }}"
                                class="btn btn-outline-secondary btn-lg mt-3 mt-md-0">
                                <i class="bx bx-arrow-back me-1"></i>Kembali
                            </a>
                        </div>

                        <!-- Alert Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-check-circle me-2"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-x-circle me-2"></i>
                                    <div>{{ session('error') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Main Content -->
                            <div class="col-lg-8">
                                <!-- Participant Card -->
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h5 class="mb-0">
                                            <i class="bx bx-user-circle me-2"></i>Informasi Peserta
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                                <div class="avatar avatar-xl bg-label-primary mx-auto mb-3">
                                                    <span class="avatar-initial rounded-circle fw-bold fs-3">
                                                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <h5 class="fw-bold">{{ $registration->user->name }}</h5>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label text-muted small">Nama Peserta</label>
                                                        <p class="mb-0 fw-semibold">{{ $registration->user->name }}</p>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label text-muted small">Email</label>
                                                        <p class="mb-0 fw-semibold">
                                                            <a
                                                                href="mailto:{{ $registration->user->email }}">{{ $registration->user->email }}</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Card -->
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h5 class="mb-0">
                                            <i class="bx bx-calendar-event me-2"></i>Informasi Event
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label class="form-label text-muted small">Nama Event</label>
                                                <p class="mb-0 fw-semibold fs-5">{{ $registration->event->title }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-muted small">
                                                    <i class="bx bx-calendar-alt me-1"></i>Tanggal Event
                                                </label>
                                                <p class="mb-0 fw-semibold">
                                                    {{ \Carbon\Carbon::parse($registration->event->date)->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-muted small">
                                                    <i class="bx bx-map me-1"></i>Lokasi Event
                                                </label>
                                                <p class="mb-0 fw-semibold">{{ $registration->event->location }}</p>
                                            </div>
                                            <div class="col-12 mb-0">
                                                <label class="form-label text-muted small">Deskripsi Event</label>
                                                <p class="mb-0">{{ $registration->event->description ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registration Card -->
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h5 class="mb-0">
                                            <i class="bx bx-clipboard me-2"></i>Informasi Pendaftaran
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-muted small">
                                                    <i class="bx bx-time-five me-1"></i>Tanggal Daftar
                                                </label>
                                                <p class="mb-0 fw-semibold">
                                                    {{ $registration->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-muted small">
                                                    <i class="bx bx-info-circle me-1"></i>Status Pendaftaran
                                                </label>
                                                <p class="mb-0">
                                                    @if ($registration->status === 'pending')
                                                        <span
                                                            class="badge bg-warning text-dark d-inline-flex align-items-center">
                                                            <i class="bx bx-time me-1"></i>Pending - Menunggu Verifikasi
                                                        </span>
                                                    @elseif($registration->status === 'diterima')
                                                        <span class="badge bg-success d-inline-flex align-items-center">
                                                            <i class="bx bx-check-circle me-1"></i>Diterima -
                                                            Pendaftaran Disetujui
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger d-inline-flex align-items-center">
                                                            <i class="bx bx-x-circle me-1"></i>Ditolak - Pendaftaran
                                                            Ditolak
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                            @if ($registration->verified_at)
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted small">
                                                        <i class="bx bx-check-double me-1"></i>Verifikasi Kehadiran
                                                    </label>
                                                    <p class="mb-0">
                                                        <span class="badge bg-info d-inline-flex align-items-center">
                                                            <i class="bx bx-check-double me-1"></i>Sudah Verifikasi
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted small">
                                                        <i class="bx bx-time-five me-1"></i>Waktu Verifikasi
                                                    </label>
                                                    <p class="mb-0 fw-semibold">
                                                        {{ $registration->verified_at->format('d M Y H:i') }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-muted small">
                                                        <i class="bx bx-user me-1"></i>Diverifikasi Oleh
                                                    </label>
                                                    <p class="mb-0 fw-semibold">{{ $registration->verified_by }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Participant Detail Card -->
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h5 class="mb-0">
                                            <i class="bx bx-user-check me-2"></i>Rincian Peserta
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-4">
                                                @if ($registration->participant_photo)
                                                    <img src="{{ asset('storage/' . $registration->participant_photo) }}"
                                                        alt="Foto Peserta" class="img-fluid rounded mb-3">
                                                @else
                                                    <div class="avatar avatar-xl bg-label-secondary mx-auto mb-3">
                                                        <span
                                                            class="avatar-initial rounded-circle fw-bold fs-3">{{ strtoupper(substr($registration->participant_name ?? $registration->user->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <p class="fw-semibold mb-0">
                                                    {{ $registration->participant_name ?? $registration->user->name }}
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-sm-6 mb-3">
                                                        <small class="text-muted">Nama Tim</small>
                                                        <p class="fw-semibold mb-0">
                                                            {{ $registration->team_name ?? '-' }}</p>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <small class="text-muted">Jurusan</small>
                                                        <p class="fw-semibold mb-0">
                                                            {{ $registration->department ?? '-' }}</p>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <small class="text-muted">Angkatan</small>
                                                        <p class="fw-semibold mb-0">{{ $registration->year ?? '-' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <small class="text-muted">Umur</small>
                                                        <p class="fw-semibold mb-0">{{ $registration->age ?? '-' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted">Kode Tiket</small>
                                                        <p class="fw-semibold mb-0">
                                                            {{ $registration->ticket_code ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Actions -->
                            <div class="col-lg-4">
                                <!-- Status Card -->
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="bx bx-info-circle me-2"></i>Status
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            @if ($registration->status === 'pending')
                                                <div class="badge bg-warning text-dark p-3"
                                                    style="font-size: 1.1rem;">
                                                    <i class="bx bx-time"></i> PENDING
                                                </div>
                                                <p class="text-muted small mt-2 mb-0">Menunggu verifikasi dari admin
                                                </p>
                                            @elseif($registration->status === 'diterima')
                                                <div class="badge bg-success p-3" style="font-size: 1.1rem;">
                                                    <i class="bx bx-check-circle"></i> DITERIMA
                                                </div>
                                                <p class="text-muted small mt-2 mb-0">Pendaftaran telah disetujui</p>
                                            @else
                                                <div class="badge bg-danger p-3" style="font-size: 1.1rem;">
                                                    <i class="bx bx-x-circle"></i> DITOLAK
                                                </div>
                                                <p class="text-muted small mt-2 mb-0">Pendaftaran telah ditolak</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Ticket Card - For Peserta Only -->
                                @if ($qrCode && Auth::user()->role !== 'admin')
                                    <div class="card shadow-sm mb-4 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">
                                                <i class="bx bx-qr me-2"></i>Tiket QR Peserta
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3 p-3 bg-light rounded">
                                                <img src="data:image/png;base64,{{ $qrCode }}"
                                                    alt="QR Ticket" class="img-fluid"
                                                    style="max-width: 200px; height: auto; image-rendering: pixelated;">
                                            </div>
                                            <p class="mb-3 text-muted small"><strong>Instruksi:</strong> Tunjukkan QR
                                                code ini kepada admin di lapangan untuk verifikasi kehadiran.</p>

                                            <div class="d-grid gap-2">
                                                <!-- Download QR -->
                                                <a href="{{ route('pendaftaran.download-qr', $registration->id) }}"
                                                    class="btn btn-success"
                                                    download="QR_{{ $registration->ticket_code }}.png">
                                                    <i class="bx bx-download me-1"></i>Unduh QR Code (PNG)
                                                </a>

                                                <!-- Download Certificate/Dokumen -->
                                                <a href="{{ route('pendaftaran.download-certificate', $registration->id) }}"
                                                    class="btn btn-primary"
                                                    download="dokumen_peserta_{{ $registration->ticket_code }}.html">
                                                    <i class="bx bx-file me-1"></i>Unduh Dokumen Peserta
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons (Admin Only) -->
                                @if (Auth::user()->role === 'admin')
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-light border-bottom">
                                            <h5 class="mb-0">
                                                <i class="bx bx-cog me-2"></i>Aksi
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-2">
                                                <!-- Approve Button -->
                                                <form
                                                    action="{{ route('pendaftaran.updateStatus', $registration->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Setujui pendaftaran ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="diterima">
                                                    <button type="submit" class="btn btn-success"
                                                        @if ($registration->status === 'diterima') disabled @endif>
                                                        <i class="bx bx-check-circle me-1"></i>Setujui Pendaftaran
                                                    </button>
                                                </form>

                                                <!-- Reject Button -->
                                                <form
                                                    action="{{ route('pendaftaran.updateStatus', $registration->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Tolak pendaftaran ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="btn btn-danger"
                                                        @if ($registration->status === 'ditolak') disabled @endif>
                                                        <i class="bx bx-x-circle me-1"></i>Tolak Pendaftaran
                                                    </button>
                                                </form>

                                                <!-- Return to Pending Button -->
                                                @if ($registration->status !== 'pending')
                                                    <form
                                                        action="{{ route('pendaftaran.updateStatus', $registration->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Kembalikan ke Pending?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="btn btn-warning">
                                                            <i class="bx bx-undo me-1"></i>Kembalikan ke Pending
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Edit Button -->
                                                <a href="{{ route('pendaftaran.edit', $registration->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="bx bx-edit me-1"></i>Edit Pendaftaran
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @include('components.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>
    @include('components.scripts')

    <script>
        // Initialize Bootstrap Tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>
