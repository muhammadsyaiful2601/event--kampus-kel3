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
                        <!-- Header -->
                        <div class="mb-4">
                            <h4 class="fw-bold py-3 mb-2">Daftar Event</h4>
                            <p class="text-muted">Pilih event yang ingin Anda ikuti dan lengkapi pendaftaran Anda sekarang.</p>
                        </div>

                        <!-- Alert Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="fw-semibold mb-2">Terjadi kesalahan:</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Form Card -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary bg-gradient">
                                <h5 class="mb-0 text-white">Form Pendaftaran Event</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pendaftaran.store') }}" method="POST" id="registrationForm">
                                    @csrf

                                    <!-- Event Selection -->
                                    <div class="mb-4">
                                        <label for="event_id" class="form-label fw-semibold">Pilih Event <span class="text-danger">*</span></label>
                                        <select 
                                            class="form-select form-select-lg @error('event_id') is-invalid @enderror" 
                                            id="event_id" 
                                            name="event_id" 
                                            required
                                            onchange="updateEventInfo()">
                                            <option value="">-- Pilih Event --</option>
                                            @forelse($events as $event)
                                                <option 
                                                    value="{{ $event->id }}" 
                                                    data-date="{{ $event->date }}"
                                                    data-location="{{ $event->location }}"
                                                    data-description="{{ $event->description }}"
                                                    data-quota="{{ $event->quota }}"
                                                    data-registered="{{ $event->registrations()->count() }}"
                                                    {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->title }} • {{ $event->date }}
                                                </option>
                                            @empty
                                                <option value="" disabled>Tidak ada event tersedia</option>
                                            @endforelse
                                        </select>
                                        @error('event_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Event Info Display -->
                                    <div id="eventInfo" style="display: none;" class="mb-4">
                                        <div class="alert alert-light border">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">Tanggal</div>
                                                    <div class="fw-semibold" id="infoDate"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">Lokasi</div>
                                                    <div class="fw-semibold" id="infoLocation"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">Deskripsi</div>
                                                    <div class="fw-semibold small" id="infoDescription"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">Kuota Peserta</div>
                                                    <div class="fw-semibold"><span id="infoRegistered">0</span>/<span id="infoQuota">-</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bx bx-check me-1"></i>Daftar Sekarang
                                        </button>
                                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="bx bx-arrow-back me-1"></i>Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Informasi Penting</h6>
                                <ul class="mb-0 ps-3">
                                    <li class="mb-2">Pastikan Anda sudah membaca deskripsi event dengan cermat sebelum mendaftar.</li>
                                    <li class="mb-2">Pendaftaran akan diproses dengan status <span class="badge bg-warning">Pending</span> terlebih dahulu.</li>
                                    <li class="mb-2">Admin akan memverifikasi pendaftaran Anda dalam waktu 24 jam.</li>
                                    <li>Anda hanya dapat mendaftar satu kali untuk setiap event.</li>
                                </ul>
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
        function updateEventInfo() {
            const select = document.getElementById('event_id');
            const selectedOption = select.options[select.selectedIndex];
            const infoDiv = document.getElementById('eventInfo');
            
            if (selectedOption.value) {
                document.getElementById('infoDate').textContent = selectedOption.dataset.date;
                document.getElementById('infoLocation').textContent = selectedOption.dataset.location;
                document.getElementById('infoDescription').textContent = selectedOption.dataset.description;
                document.getElementById('infoQuota').textContent = selectedOption.dataset.quota || 'Unlimited';
                document.getElementById('infoRegistered').textContent = selectedOption.dataset.registered;
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateEventInfo();
        });
    </script>
</body>
</html>
</body>
</html>
