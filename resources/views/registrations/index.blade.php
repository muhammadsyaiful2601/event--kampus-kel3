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
                                <h4 class="fw-bold py-3 mb-2">Daftar Peserta Event</h4>
                                <p class="text-muted mb-0">Kelola pendaftaran peserta dan ubah status verifikasi dengan
                                    mudah.</p>
                            </div>
                            <div class="d-flex gap-2">
                                @if (Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.registrations.scan') }}"
                                        class="btn btn-info btn-lg mt-3 mt-md-0">
                                        <i class="bx bx-qr me-1"></i>Scan QR Peserta
                                    </a>
                                @endif
                                @if (Auth::user()->role !== 'admin')
                                    <a href="{{ route('pendaftaran.create') }}"
                                        class="btn btn-primary btn-lg mt-3 mt-md-0">
                                        <i class="bx bx-plus me-1"></i>Daftar Event Baru
                                    </a>
                                @endif
                            </div>
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

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-label-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted small">Total Pendaftar</p>
                                                <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                                            </div>
                                            <div class="avatar bg-primary rounded">
                                                <i class="bx bx-user fs-4 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-label-warning h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted small">Pending</p>
                                                <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
                                            </div>
                                            <div class="avatar bg-warning rounded">
                                                <i class="bx bx-time fs-4 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-label-success h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted small">Diterima</p>
                                                <h3 class="mb-0 fw-bold">{{ $stats['diterima'] }}</h3>
                                            </div>
                                            <div class="avatar bg-success rounded">
                                                <i class="bx bx-check-circle fs-4 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-label-danger h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted small">Ditolak</p>
                                                <h3 class="mb-0 fw-bold">{{ $stats['ditolak'] }}</h3>
                                            </div>
                                            <div class="avatar bg-danger rounded">
                                                <i class="bx bx-x-circle fs-4 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('pendaftaran.index') }}" method="GET" class="row g-3">
                                    <div class="col-md-6">
                                        <label for="search" class="form-label">Cari</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            placeholder="Nama peserta, email, atau nama event..."
                                            value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Filter Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="semua"
                                                {{ request('status', 'semua') === 'semua' ? 'selected' : '' }}>Semua
                                                Status</option>
                                            <option value="pending"
                                                {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="diterima"
                                                {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima
                                            </option>
                                            <option value="ditolak"
                                                {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bx bx-search me-1"></i>Cari
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Table Card -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bx bx-list-ul me-2"></i>Data Pendaftaran
                                    </h5>
                                    <span class="badge bg-primary">{{ $registrations->total() }} Pendaftaran</span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="fw-semibold">
                                                <i class="bx bx-user me-1"></i>Nama Peserta
                                            </th>
                                            <th scope="col" class="fw-semibold">
                                                <i class="bx bx-calendar me-1"></i>Nama Event
                                            </th>
                                            <th scope="col" class="fw-semibold">
                                                <i class="bx bx-info-circle me-1"></i>Status
                                            </th>
                                            @if (Auth::user()->role === 'admin')
                                                <th scope="col" class="fw-semibold">
                                                    <i class="bx bx-check-double me-1"></i>Verifikasi Kehadiran
                                                </th>
                                            @endif
                                            <th scope="col" class="fw-semibold">
                                                <i class="bx bx-time-five me-1"></i>Tanggal Daftar
                                            </th>
                                            <th scope="col" class="fw-semibold">
                                                <i class="bx bx-cog me-1"></i>Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top">
                                        @forelse($registrations as $registration)
                                            <tr>
                                                <!-- Nama Peserta -->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm bg-label-primary me-3">
                                                            <span class="avatar-initial rounded-circle">
                                                                {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="fw-semibold">{{ $registration->user->name }}</span>
                                                            @if (Auth::user()->role === 'admin')
                                                                <small
                                                                    class="text-muted">{{ $registration->user->email }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Nama Event -->
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="fw-semibold">{{ $registration->event->title }}</span>
                                                        <small class="text-muted">
                                                            <i
                                                                class="bx bx-calendar-alt"></i>{{ $registration->event->date }}
                                                        </small>
                                                    </div>
                                                </td>

                                                <!-- Status -->
                                                <td>
                                                    @if ($registration->status === 'pending')
                                                        <span
                                                            class="badge bg-warning text-dark d-inline-flex align-items-center">
                                                            <i class="bx bx-time me-1"></i>Pending
                                                        </span>
                                                    @elseif($registration->status === 'diterima')
                                                        <span
                                                            class="badge bg-success d-inline-flex align-items-center">
                                                            <i class="bx bx-check-circle me-1"></i>Diterima
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger d-inline-flex align-items-center">
                                                            <i class="bx bx-x-circle me-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                </td>

                                                @if (Auth::user()->role === 'admin')
                                                    <!-- Verification Status -->
                                                    <td>
                                                        @if ($registration->verified_at)
                                                            <div class="d-flex flex-column">
                                                                <span
                                                                    class="badge bg-info d-inline-flex align-items-center w-fit">
                                                                    <i
                                                                        class="bx bx-check-double me-1"></i>Terverifikasi
                                                                </span>
                                                                <small
                                                                    class="text-muted mt-1">{{ $registration->verified_at->format('d M Y H:i') }}</small>
                                                                <small
                                                                    class="text-muted">{{ $registration->verified_by }}</small>
                                                            </div>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Verifikasi</span>
                                                        @endif
                                                    </td>
                                                @endif

                                                <!-- Tanggal Daftar -->
                                                <td>
                                                    <small
                                                        class="text-muted">{{ $registration->created_at->format('d M Y H:i') }}</small>
                                                </td>

                                                <!-- Aksi -->
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <!-- Detail Button -->
                                                        <a href="{{ route('pendaftaran.show', $registration->id) }}"
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Lihat Detail">
                                                            <i class="bx bx-show"></i>
                                                        </a>

                                                        @if (Auth::user()->role === 'admin')
                                                            <!-- Approve Button (Set to Diterima) -->
                                                            <form
                                                                action="{{ route('pendaftaran.updateStatus', $registration->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Setujui pendaftaran ini?');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status"
                                                                    value="diterima">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-success"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Setujui"
                                                                    @if ($registration->status === 'diterima') disabled @endif>
                                                                    <i class="bx bx-check"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Reject Button (Set to Ditolak) -->
                                                            <form
                                                                action="{{ route('pendaftaran.updateStatus', $registration->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Tolak pendaftaran ini?');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="ditolak">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Tolak"
                                                                    @if ($registration->status === 'ditolak') disabled @endif>
                                                                    <i class="bx bx-x"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Return to Pending Button (Set to Pending) -->
                                                            @if ($registration->status !== 'pending')
                                                                <form
                                                                    action="{{ route('pendaftaran.updateStatus', $registration->id) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Kembalikan ke Pending?');">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status"
                                                                        value="pending">
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Kembalikan ke Pending">
                                                                        <i class="bx bx-undo"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <!-- Edit Button -->
                                                            <a href="{{ route('pendaftaran.edit', $registration->id) }}"
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Edit">
                                                                <i class="bx bx-edit"></i>
                                                            </a>
                                                        @endif

                                                        @if (Auth::user()->role === 'admin' || Auth::id() === $registration->user_id)
                                                            <!-- Delete Button -->
                                                            <form
                                                                action="{{ route('pendaftaran.destroy', $registration->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Hapus">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="bx bx-inbox" style="font-size: 3rem;"></i>
                                                        <p class="mt-2 fw-semibold">Belum ada pendaftaran</p>
                                                        <small>Mulai daftar event baru untuk melihat data di
                                                            sini.</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if ($registrations->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $registrations->links() }}
                            </div>
                        @endif

                        <!-- Footer Info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <h6 class="fw-bold mb-2">Keterangan Status:</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            <small class="ms-2">Menunggu verifikasi admin</small>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="badge bg-success">Diterima</span>
                                            <small class="ms-2">Pendaftaran telah disetujui</small>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="badge bg-danger">Ditolak</span>
                                            <small class="ms-2">Pendaftaran ditolak oleh admin</small>
                                        </div>
                                    </div>
                                </div>
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
