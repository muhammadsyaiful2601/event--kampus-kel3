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

                        {{-- Isi Konten Halaman Mulai di Sini --}}
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div
                                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button"
                                class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                aria-label="Toggle menu">
                                <i class="bx bx-menu"></i>
                            </button>
                            <div>
                                <h4 class="fw-bold mb-2">{{ __('messages.dashboard_admin') }}</h4>
                                <p class="mb-0 text-muted">{{ __('messages.welcome_message', ['name' => Auth::user()->name]) }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.events.index') }}"
                                class="btn btn-outline-primary btn-sm">{{ __('messages.event_management') }}</a>
                            <a href="{{ route('admin.registrations.index') }}"
                                class="btn btn-outline-success btn-sm">{{ __('messages.registration_verification') }}</a>
                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center py-4">
                                <span class="badge bg-label-primary mb-3">{{ __('messages.total_events') }}</span>
                                <h2 class="mb-0">{{ $eventCount ?? 0 }}</h2>
                                <p class="text-muted mb-0">{{ __('messages.all_events') }}</p>
                            </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center py-4">
                                <span class="badge bg-label-info mb-3">{{ __('messages.ongoing_events') }}</span>
                                <h2 class="mb-0">{{ $eventOngoing ?? 0 }}</h2>
                                <p class="text-muted mb-0">{{ __('messages.current_events') }}</p>
                            </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center py-4">
                                <span class="badge bg-label-success mb-3">{{ __('messages.accepted_registrations') }}</span>
                                <h2 class="mb-0">{{ $acceptedRegistrations ?? 0 }}</h2>
                                <p class="text-muted mb-0">{{ __('messages.passed_participants') }}</p>
                            </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center py-4">
                                <span class="badge bg-label-warning mb-3">{{ __('messages.pending_registrations') }}</span>
                                <h2 class="mb-0">{{ $pendingRegistrations ?? 0 }}</h2>
                                <p class="text-muted mb-0">{{ __('messages.waiting_verification') }}</p>
                            </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ __('messages.registration_summary') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4 border-end">
                                                <p class="mb-1 text-muted">{{ __('messages.safe') }}</p>
                                                <h4 class="mb-0">{{ $acceptedRegistrations ?? 0 }}</h4>
                                            </div>
                                            <div class="col-4 border-end">
                                                <p class="mb-1 text-muted">{{ __('messages.pending') }}</p>
                                                <h4 class="mb-0">{{ $pendingRegistrations ?? 0 }}</h4>
                                            </div>
                                            <div class="col-4">
                                                <p class="mb-1 text-muted">{{ __('messages.rejected') }}</p>
                                                <h4 class="mb-0">{{ $rejectedRegistrations ?? 0 }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ __('messages.active_admins') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div
                                            class="d-flex align-items-center justify-content-between gap-3 flex-column flex-sm-row">
                                            <div>
                                                <p class="text-muted mb-1">{{ __('messages.current_admin_count') }}</p>
                                                <h3 class="mb-0">{{ $adminCount ?? 0 }}</h3>
                                            </div>
                                            <a href="{{ route('admin.admins.index') }}"
                                                class="btn btn-outline-secondary btn-sm">{{ __('messages.view_admins') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('messages.latest_registrations') }}</h5>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.event') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentRegistrations ?? collect() as $registration)
                                            <tr>
                                                <td>{{ $registration->user->name ?? '-' }}</td>
                                                <td>{{ $registration->event->title ?? '-' }}</td>
                                                <td>
                                                    @if ($registration->status === 'diterima')
                                                        <span class="badge bg-success">{{ __('messages.diterima') }}</span>
                                                    @elseif($registration->status === 'ditolak')
                                                        <span class="badge bg-danger">{{ __('messages.ditolak') }}</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">{{ __('messages.pending') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $registration->created_at ? $registration->created_at->format('d M Y') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">{{ __('messages.no_latest_registrations') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Batas Akhir Konten Halaman --}}

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
