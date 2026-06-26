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

                        {{-- Page Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button"
                                    class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                    aria-label="Toggle menu">
                                    <i class="bx bx-menu"></i>
                                </button>
                                <div>
                                    <h4 class="fw-bold mb-0">📋 Log Aktivitas Admin</h4>
                                    <small class="text-muted">Catatan semua tindakan admin — hanya baca, tidak dapat dihapus</small>
                                </div>
                            </div>
                            <span class="badge bg-label-danger">
                                <i class='bx bx-lock-alt me-1'></i>Immutable Log
                            </span>
                        </div>

                        {{-- Session alerts --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Filter Card --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class='bx bx-filter me-1'></i>Filter Log</h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.logs.index') }}">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold">Kategori Tindakan</label>
                                            <select name="action" class="form-select form-select-sm">
                                                @foreach($actionCategories as $key => $label)
                                                    <option value="{{ $key }}" {{ request('action', 'semua') == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold">Admin</label>
                                            <select name="admin_id" class="form-select form-select-sm">
                                                <option value="semua">Semua Admin</option>
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                                        {{ $admin->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-semibold">Dari Tanggal</label>
                                            <input type="date" name="date_from" class="form-control form-control-sm"
                                                value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-semibold">Sampai Tanggal</label>
                                            <input type="date" name="date_to" class="form-control form-control-sm"
                                                value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class='bx bx-search me-1'></i>Filter
                                            </button>
                                            <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class='bx bx-reset'></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Log Table --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Total: {{ $logs->total() }} entri log</span>
                                <span class="text-muted small">Menampilkan {{ $logs->count() }} dari {{ $logs->total() }}</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:160px">Waktu</th>
                                            <th style="width:150px">Admin</th>
                                            <th style="width:180px">Tindakan</th>
                                            <th>Deskripsi</th>
                                            <th style="width:130px">IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold small">{{ $log->created_at->format('d M Y') }}</div>
                                                    <div class="text-muted small">{{ $log->created_at->format('H:i:s') }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold small">{{ $log->admin_name }}</div>
                                                    @if($log->admin)
                                                        <div class="text-muted small">{{ $log->admin->email }}</div>
                                                    @else
                                                        <div class="text-muted small fst-italic">Akun dihapus</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $log->action_color }} text-wrap text-start lh-sm py-1 px-2">
                                                        {{ $log->action_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="small">{{ $log->description }}</span>
                                                    @if($log->subject_type && $log->subject_id)
                                                        <div class="text-muted small">
                                                            <i class='bx bx-link-alt'></i>
                                                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-secondary font-monospace small">
                                                        {{ $log->ip_address ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="mb-2 fs-1">📋</div>
                                                    <h6 class="text-muted">Belum ada catatan log</h6>
                                                    <p class="text-muted small">Log akan muncul secara otomatis setelah admin melakukan tindakan.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if($logs->hasPages())
                                <div class="card-footer d-flex justify-content-center">
                                    {{ $logs->links() }}
                                </div>
                            @endif
                        </div>

                        {{-- Info note --}}
                        <div class="alert alert-info d-flex align-items-start mt-4" role="alert">
                            <i class='bx bx-info-circle me-2 fs-5 mt-1 flex-shrink-0'></i>
                            <div class="small">
                                <strong>Catatan Keamanan:</strong> Log ini bersifat <em>hanya-baca</em> dan tidak dapat dihapus atau dimodifikasi oleh siapapun melalui aplikasi. Setiap tindakan admin dicatat secara otomatis beserta nama, waktu, dan alamat IP.
                            </div>
                        </div>

                    </div>
                    @include('components.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('components.scripts')
</body>
</html>
