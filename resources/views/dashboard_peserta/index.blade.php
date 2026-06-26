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


                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <button type="button"
                                                class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                                aria-label="Toggle menu">
                                                <i class="bx bx-menu"></i>
                                            </button>
                                            <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Dashboard
                                                    /</span> Beranda</h4>
                                        </div>
                                        <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Di sini kamu bisa
                                            memantau pendaftaranmu dan memilih event baru.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pendaftaran Summary -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="fw-semibold mb-2">Periksa kembali form pendaftaran:</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row mb-5">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-label-warning">
                                    <div class="card-body text-center">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="avatar-initial rounded bg-warning"><i
                                                    class="bx bx-time-five fs-4"></i></span>
                                        </div>
                                        <h5 class="card-title mb-1">Pending</h5>
                                        <p class="mb-0 fw-bold fs-4">
                                            {{ $pendingCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-label-success">
                                    <div class="card-body text-center">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="avatar-initial rounded bg-success"><i
                                                    class="bx bx-check-circle fs-4"></i></span>
                                        </div>
                                        <h5 class="card-title mb-1">Diterima</h5>
                                        <p class="mb-0 fw-bold fs-4">
                                            {{ $acceptedCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-label-danger">
                                    <div class="card-body text-center">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="avatar-initial rounded bg-danger"><i
                                                    class="bx bx-x-circle fs-4"></i></span>
                                        </div>
                                        <h5 class="card-title mb-1">Ditolak</h5>
                                        <p class="mb-0 fw-bold fs-4">
                                            {{ $rejectedCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Participant registration history --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Riwayat Pendaftaran Saya</h5>
                            </div>
                            <div class="card-body">
                                @if ($myRegistrations->isEmpty())
                                    <div class="alert alert-info">Belum ada pendaftaran event yang terdaftar.</div>
                                @else
                                    <div class="list-group">
                                        @foreach ($myRegistrations as $registration)
                                            <div class="list-group-item border-0 shadow-sm mb-3">
                                                <div
                                                    class="d-flex justify-content-between align-items-start flex-column flex-md-row gap-3">
                                                    <div>
                                                        <h6 class="mb-1">{{ $registration->event->title }}</h6>
                                                        <p class="mb-1 text-muted small">
                                                            {{ \Carbon\Carbon::parse($registration->created_at)->format('d M Y H:i') }}
                                                        </p>
                                                        @if ($registration->status === 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @elseif($registration->status === 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-end d-flex flex-column align-items-end gap-2">
                                                        @if ($registration->status === 'diterima')
                                                            <a href="{{ route('pendaftaran.show', $registration->id) }}"
                                                                class="btn btn-sm btn-success">Lihat Tiket</a>
                                                            <div class="text-muted small" style="max-width: 200px;">
                                                                <i class='bx bx-info-circle text-warning'></i>
                                                                Untuk pengunduran diri, hubungi admin secara langsung di lapangan.
                                                            </div>
                                                        @elseif($registration->status === 'ditolak')
                                                            <div class="text-danger small">Data kurang atau tidak masuk
                                                                kategori.</div>
                                                        @else
                                                            <div class="text-muted small mb-1">Menunggu verifikasi admin.</div>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-cancel-reg"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#cancelModal"
                                                                data-reg-id="{{ $registration->id }}"
                                                                data-event-title="{{ $registration->event->title }}"
                                                                data-cancel-url="{{ route('pendaftaran.cancel', $registration->id) }}">
                                                                <i class='bx bx-x-circle me-1'></i>Batalkan Pendaftaran
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-5">
                        <h4 class="fw-bold mb-4">Daftar Event Tersedia</h4>

                        <h5 class="pb-1 mb-4">Event Sedang Berlangsung</h5>
                        <div class="row g-4 mb-5">
                            @forelse($ongoingEvents as $event)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 border-top border-4 border-primary">
                                        @if ($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top"
                                                alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-primary">Berlangsung</span>
                                                <span
                                                    class="badge bg-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                            </div>
                                            <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                                            <p class="card-text text-muted small"><i class='bx bx-calendar'></i>
                                                {{ $event->date }} | <i class='bx bx-map'></i>
                                                {{ $event->location }}
                                            </p>
                                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                            <div class="text-muted small italic">Pendaftaran ditutup untuk event ini
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-secondary text-center">Tidak ada event yang sedang
                                        berlangsung.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <h5 class="pb-1 mb-4">Event Mendatang</h5>
                        <div class="row g-4">
                            @forelse($upcomingEvents as $event)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        @if ($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top"
                                                alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-label-secondary">Mendatang</span>
                                                <span
                                                    class="badge bg-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                                <span class="badge bg-label-primary">Kuota:
                                                    {{ $event->quota ?? 'Unlimited' }}</span>
                                            </div>
                                            <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                                            <p class="card-text text-muted small"><i class='bx bx-calendar'></i>
                                                {{ $event->date }} | <i class='bx bx-map'></i>
                                                {{ $event->location }}
                                            </p>
                                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                            <div class="mt-3">
                                                @if (!$event->is_registration_open)
                                                    <span class="badge bg-secondary">Pendaftaran Ditutup</span>
                                                @elseif($event->is_full)
                                                    <span class="badge bg-danger">Penuh</span>
                                                @else
                                                    @php
                                                        $isRegistered = \App\Models\Registration::where(
                                                            'user_id',
                                                            Auth::id(),
                                                        )
                                                            ->where('event_id', $event->id)
                                                            ->exists();
                                                    @endphp
                                                    @if ($isRegistered)
                                                        <button class="btn btn-success w-100"
                                                            disabled>Terdaftar</button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-primary w-100 register-btn"
                                                            data-bs-toggle="modal" data-bs-target="#registrationModal"
                                                            data-event-id="{{ $event->id }}"
                                                            data-event-title="{{ $event->title }}"
                                                            data-event-type="{{ $event->type }}"
                                                            data-url="{{ route('pendaftaran.store') }}">
                                                            Daftar Sekarang
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-secondary text-center">Belum ada event mendatang.</div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Cancel Confirmation Modal -->
                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title text-danger"><i class='bx bx-error-circle me-1'></i>Batalkan Pendaftaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah kamu yakin ingin membatalkan pendaftaran untuk event:</p>
                                        <p class="fw-bold" id="cancelEventTitle"></p>
                                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tidak, Kembali</button>
                                        <form id="cancelForm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Modal -->
                        <div class="modal fade" id="registrationModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="registrationForm" method="POST"
                                        action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="event_id" id="event_id_input" value="">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Daftar Event</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 id="eventTitleDisplay" class="fw-bold mb-3"></h6>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label for="participant_name" class="form-label">Nama Peserta
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="participant_name"
                                                        name="participant_name" placeholder="Masukkan nama peserta"
                                                        required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="department" class="form-label">Jurusan <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="department"
                                                        name="department" placeholder="Masukkan jurusan" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="year" class="form-label">Angkatan <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="year"
                                                        name="year" placeholder="Masukkan angkatan" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="age" class="form-label">Umur <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="age"
                                                        name="age" placeholder="Masukkan umur" min="10"
                                                        max="120" required>
                                                </div>
                                            </div>

                                            <div id="teamFields" style="display: none;" class="mb-3">
                                                <label for="team_name" class="form-label">Nama Tim</label>
                                                <input type="text" class="form-control" id="team_name"
                                                    name="team_name" placeholder="Masukkan nama tim">
                                            </div>
                                            <div class="mb-3">
                                                <label for="participant_photo_modal" class="form-label">Foto Peserta
                                                    <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control"
                                                    id="participant_photo_modal" name="participant_photo"
                                                    accept=".png,.jpg,.jpeg" required>
                                            </div>

                                            <p id="confirmationText">Apakah kamu yakin ingin mendaftar di event ini?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Konfirmasi
                                                Pendaftaran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <style>
                            .btn-primary {
                                background: linear-gradient(45deg, #696cff, #8592ff);
                                border: none;
                            }

                            .btn-primary:hover {
                                opacity: 0.9;
                            }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Cancel modal handler
                                var cancelModal = document.getElementById('cancelModal');
                                if (cancelModal) {
                                    cancelModal.addEventListener('show.bs.modal', function(event) {
                                        var button = event.relatedTarget;
                                        var eventTitle = button.getAttribute('data-event-title');
                                        var cancelUrl = button.getAttribute('data-cancel-url');
                                        document.getElementById('cancelEventTitle').textContent = eventTitle;
                                        document.getElementById('cancelForm').setAttribute('action', cancelUrl);
                                    });
                                }

                                // Registration modal handler
                                var registrationModal = document.getElementById('registrationModal');
                                registrationModal.addEventListener('show.bs.modal', function(event) {
                                    var button = event.relatedTarget;
                                    var eventTitle = button.getAttribute('data-event-title');
                                    var eventType = button.getAttribute('data-event-type');
                                    var url = button.getAttribute('data-url');
                                    var eventId = button.getAttribute('data-event-id');

                                    var modalTitle = registrationModal.querySelector('.modal-title');
                                    var eventTitleDisplay = registrationModal.querySelector('#eventTitleDisplay');
                                    var form = registrationModal.querySelector('#registrationForm');
                                    var teamFields = registrationModal.querySelector('#teamFields');
                                    var teamNameInput = registrationModal.querySelector('#team_name');
                                    var confirmationText = registrationModal.querySelector('#confirmationText');

                                    var eventTypeLabel = eventType ? (eventType.charAt(0).toUpperCase() + eventType.slice(1)) :
                                        'Event';
                                    modalTitle.textContent = 'Pendaftaran ' + eventTypeLabel;
                                    eventTitleDisplay.textContent = eventTitle;
                                    form.setAttribute('action', url);

                                    var eventInput = form.querySelector('#event_id_input');
                                    if (eventInput) {
                                        eventInput.value = eventId;
                                    }

                                    if (eventType === 'tim') {
                                        teamFields.style.display = 'block';
                                        teamNameInput.setAttribute('required', 'required');
                                        confirmationText.style.display = 'none';
                                    } else {
                                        teamFields.style.display = 'none';
                                        teamNameInput.removeAttribute('required');
                                        confirmationText.style.display = 'block';
                                    }
                                });
                            });
                        </script>

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
