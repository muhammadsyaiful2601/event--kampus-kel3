<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

@include('components.header')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('components.sidebar')
            <div class="layout-page">
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Header Section -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h4 class="fw-bold py-3 mb-2">{{ __('messages.edit_registration_status_title') }}</h4>
                                <p class="text-muted mb-0">{{ __('messages.edit_registration_status_description') }}</p>
                            </div>
                            <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary btn-lg mt-3 mt-md-0">
                                <i class="bx bx-arrow-back me-1"></i>Kembali
                            </a>
                        </div>

                        <!-- Alert Messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-check-circle me-2"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-x-circle me-2"></i>
                                    <div class="fw-semibold">{{ __('messages.error_occurred') }}</div>
                                </div>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Main Content -->
                        <div class="row">
                            <!-- Information Cards -->
                            <div class="col-lg-4 mb-4">
                                <!-- Peserta Info -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="bx bx-user-circle me-2"></i>{{ __('messages.participant_information') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar avatar-md bg-label-primary me-3">
                                                <span class="avatar-initial rounded-circle fw-bold fs-5">
                                                    {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="mb-1 fw-semibold">{{ $registration->user->name }}</p>
                                                <small class="text-muted">
                                                    <a href="mailto:{{ $registration->user->email }}">{{ $registration->user->email }}</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Info -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="bx bx-calendar-event me-2"></i>{{ __('messages.event_information') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">{{ __('messages.event_name') }}</small>
                                            <p class="fw-semibold mb-0">{{ $registration->event->title }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">{{ __('messages.event_date') }}</small>
                                            <p class="fw-semibold mb-0">
                                                <i class="bx bx-calendar"></i> {{ \Carbon\Carbon::parse($registration->event->date)->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">{{ __('messages.event_location') }}</small>
                                            <p class="fw-semibold mb-0">
                                                <i class="bx bx-map"></i> {{ $registration->event->location }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status -->
                                <div class="card shadow-sm">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="bx bx-time-five me-2"></i>{{ __('messages.current_status') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            @if($registration->status === 'pending')
                                                <span class="badge bg-warning text-dark p-3" style="font-size: 1rem;">
                                                    <i class="bx bx-time"></i> {{ __('messages.pending') }}
                                                </span>
                                                <p class="text-muted small mt-2 mb-0">{{ __('messages.waiting_verification') }}</p>
                                            @elseif($registration->status === 'diterima')
                                                <span class="badge bg-success p-3" style="font-size: 1rem;">
                                                    <i class="bx bx-check-circle"></i> {{ __('messages.diterima') }}
                                                </span>
                                                <p class="text-muted small mt-2 mb-0">{{ __('messages.registration_approved') }}</p>
                                            @else
                                                <span class="badge bg-danger p-3" style="font-size: 1rem;">
                                                    <i class="bx bx-x-circle"></i> {{ __('messages.ditolak') }}
                                                </span>
                                                <p class="text-muted small mt-2 mb-0">{{ __('messages.registration_rejected') }}</p>
                                            @endif
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">{{ __('messages.registration_date') }}</small>
                                            <p class="fw-semibold mb-0">{{ $registration->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Update Form -->
                            <div class="col-lg-8">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">
                                            <i class="bx bx-edit-alt me-2"></i>{{ __('messages.edit_registration_status_title') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('pendaftaran.updateStatus', $registration->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <!-- Status Selection -->
                                            <div class="mb-4">
                                                <label for="status" class="form-label fw-semibold">
                                                    <i class="bx bx-info-circle me-2"></i>{{ __('messages.select_new_status') }} <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select form-select-lg @error('status') is-invalid @enderror" 
                                                        id="status" 
                                                        name="status" 
                                                        required
                                                        onchange="updateStatusPreview()">
                                                    <option value="">-- {{ __('messages.select_status') }} --</option>
                                                    <option value="pending" data-color="warning" data-icon="bx-time">
                                                        <i class="bx bx-time"></i> Pending - Menunggu Verifikasi
                                                    </option>
                                                    <option value="diterima" data-color="success" data-icon="bx-check-circle">
                                                        <i class="bx bx-check-circle"></i> Diterima - Pendaftaran Disetujui
                                                    </option>
                                                    <option value="ditolak" data-color="danger" data-icon="bx-x-circle">
                                                        <i class="bx bx-x-circle"></i> Ditolak - Pendaftaran Ditolak
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Status Preview -->
                                            <div class="mb-4" id="statusPreview" style="display: none;">
                                                <label class="form-label fw-semibold">
                                                    <i class="bx bx-eye me-2"></i>Preview Status Baru
                                                </label>
                                                <div class="alert alert-light border d-flex align-items-center">
                                                    <span class="badge p-3 me-3" id="previewBadge"></span>
                                                    <span id="previewText" class="text-muted"></span>
                                                </div>
                                            </div>

                                            <!-- Status Guide -->
                                            <div class="alert alert-info" role="alert">
                                                <h6 class="alert-heading fw-semibold">
                                                    <i class="bx bx-lightbulb me-2"></i>Panduan Status
                                                </h6>
                                                <ul class="mb-0 small">
                                                    <li><strong>Pending:</strong> Pendaftaran masih menunggu verifikasi admin</li>
                                                    <li><strong>Diterima:</strong> Pendaftaran telah disetujui dan peserta dapat mengikuti event</li>
                                                    <li><strong>Ditolak:</strong> Pendaftaran ditolak karena alasan tertentu</li>
                                                </ul>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="bx bx-save me-1"></i>{{ __('messages.save_changes') }}
                                                </button>
                                                <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary btn-lg">
                                                    <i class="bx bx-arrow-back me-1"></i>{{ __('messages.cancel') }}
                                                </a>
                                            </div>
                                        </form>
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
        function updateStatusPreview() {
            const selectElement = document.getElementById('status');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const previewDiv = document.getElementById('statusPreview');
            const previewBadge = document.getElementById('previewBadge');
            const previewText = document.getElementById('previewText');

            if (selectedOption.value) {
                const color = selectedOption.dataset.color;
                const icon = selectedOption.dataset.icon;
                const text = selectedOption.text;

                previewBadge.className = `badge bg-${color} p-3`;
                previewBadge.innerHTML = `<i class="bx ${icon} me-1"></i>${text.split(' - ')[0].trim()}`;
                previewText.textContent = text.split(' - ')[1] || '';
                previewDiv.style.display = 'block';
            } else {
                previewDiv.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStatusPreview();
        });
    </script>
</body>
</html>
