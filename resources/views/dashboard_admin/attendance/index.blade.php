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

                        {{-- Header --}}
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <button type="button"
                                class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                aria-label="Toggle menu">
                                <i class="bx bx-menu"></i>
                            </button>
                            <div class="avatar avatar-md flex-shrink-0">
                                <span class="avatar-initial rounded-3 bg-label-primary">
                                    <i class="bx bx-check-double fs-3"></i>
                                </span>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ __('messages.attendance_management') }}</h4>
                                <small class="text-muted">{{ __('messages.attendance_management_desc') }}</small>
                            </div>
                        </div>

                        {{-- Alerts --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bx bx-check-circle fs-5 me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bx bx-x-circle fs-5 me-2"></i>
                                <div>{{ session('error') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Cards --}}
                        @if ($events->count())
                            <div class="row g-3">
                                @foreach($events as $event)
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body p-3 d-flex flex-column">
                                                {{-- Top section: title + badge --}}
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $event->title }}</h6>
                                                    <small class="text-muted text-truncate d-block mb-2">
                                                        <i class="bx bx-map-pin me-1"></i>{{ $event->location }}
                                                    </small>
                                                    <span class="badge bg-{{ $event->status === 'berlangsung' ? 'success' : 'info' }}">
                                                        {{ $event->status === 'berlangsung' ? __('messages.ongoing') : __('messages.upcoming') }}
                                                    </span>
                                                </div>

                                                {{-- Date --}}
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <i class="bx bx-calendar text-muted flex-shrink-0"></i>
                                                    <small>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</small>
                                                </div>

                                                {{-- Spacer --}}
                                                <div class="flex-grow-1"></div>

                                                {{-- Attendance status --}}
                                                <div class="mb-3">
                                                    @if ($event->is_attendance_open)
                                                        <div class="d-flex align-items-center gap-1 text-success fw-semibold small">
                                                            <i class="bx bx-check-circle"></i>
                                                            <span>{{ __('messages.attendance_open') }}</span>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center gap-1 text-danger fw-semibold small">
                                                            <i class="bx bx-x-circle"></i>
                                                            <span>{{ __('messages.attendance_closed') }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Actions --}}
                                                <div class="d-flex gap-2 pt-2 border-top">
                                                    @if ($event->is_attendance_open)
                                                        <form action="{{ route('admin.attendance.close', $event) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-danger btn-sm fw-semibold">
                                                                <i class="bx bx-lock-alt me-1"></i>{{ __('messages.close_session') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.attendance.open', $event) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm fw-semibold">
                                                                <i class="bx bx-unlock me-1"></i>{{ __('messages.open_session') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('admin.registrations.scan') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                                        <i class="bx bx-qr-scan me-1"></i>{{ __('messages.scan_qr') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-5">
                                    <i class="bx bx-calendar-x display-5 mb-3 d-block text-muted"></i>
                                    <p class="fw-semibold text-muted mb-0">{{ __('messages.no_events') }}</p>
                                </div>
                            </div>
                        @endif

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