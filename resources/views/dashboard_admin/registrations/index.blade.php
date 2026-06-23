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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold">{{ __('messages.registration_verification') }}</h4>
                            <a href="{{ route('admin.registrations.scan') }}" class="btn btn-primary">
                                <i class="bx bx-scan me-1"></i>Scan QR Peserta
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="mb-1 text-muted small">Total Pendaftaran</p>
                                        <h3 class="mb-0 fw-bold">{{ $registrations->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="mb-1 text-muted small">Pending</p>
                                        <h3 class="mb-0 fw-bold">
                                            {{ $registrations->where('status', 'pending')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="mb-1 text-muted small">Diterima</p>
                                        <h3 class="mb-0 fw-bold">
                                            {{ $registrations->where('status', 'diterima')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <p class="mb-1 text-muted small">Ditolak</p>
                                        <h3 class="mb-0 fw-bold">
                                            {{ $registrations->where('status', 'ditolak')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.participant') }}</th>
                                            <th>Event</th>
                                            <th>{{ __('messages.registration_date') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($registrations as $reg)
                                            <tr>
                                                <td>
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $reg->user->name }}</span>
                                                            <small class="text-muted">{{ $reg->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $reg->event->title }}</td>
                                                <td>{{ $reg->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if ($reg->status === 'pending')
                                                        <span
                                                            class="badge bg-label-warning">{{ __('messages.pending') }}</span>
                                                    @elseif($reg->status === 'diterima')
                                                        <span
                                                            class="badge bg-label-success">{{ __('messages.diterima') }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-label-danger">{{ __('messages.ditolak') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($reg->status === 'pending')
                                                        <div class="d-flex gap-2">
                                                            <form
                                                                action="{{ route('admin.registrations.verify', $reg->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="diterima">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-success">{{ __('messages.verify') }}</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('admin.registrations.verify', $reg->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="ditolak">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger">{{ __('messages.reject') }}</button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span
                                                            class="text-muted small">{{ __('messages.finished') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="mb-3">
                                                        <h5 class="mb-1">Tidak ada pendaftaran saat ini</h5>
                                                        <p class="text-muted">Belum ada peserta yang mendaftar. Anda
                                                            dapat membuat event baru atau menggunakan fitur scan untuk
                                                            memverifikasi peserta saat tiba.</p>
                                                    </div>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('admin.events.index') }}"
                                                            class="btn btn-primary">Buat Event Baru</a>
                                                        <a href="{{ route('admin.registrations.scan') }}"
                                                            class="btn btn-outline-secondary">Scan QR</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
</body>

</html>
