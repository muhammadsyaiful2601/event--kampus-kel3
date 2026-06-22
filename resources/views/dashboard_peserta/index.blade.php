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
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Dashboard /</span> Beranda</h4>
                                        <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Di sini kamu bisa memantau pendaftaranmu dan memilih event baru.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pendaftaran Summary -->
                        <div class="row mb-5">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-label-primary">
                                    <div class="card-body text-center">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="avatar-initial rounded bg-primary"><i class="bx bx-time-five fs-4"></i></span>
                                        </div>
                                        <h5 class="card-title mb-1">Pending</h5>
                                        <p class="mb-0 fw-bold fs-4">{{ $myRegistrations->where('status', 'pending')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-label-success">
                                    <div class="card-body text-center">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="avatar-initial rounded bg-success"><i class="bx bx-check-circle fs-4"></i></span>
                                        </div>
                                        <h5 class="card-title mb-1">Verified</h5>
                                        <p class="mb-0 fw-bold fs-4">{{ $myRegistrations->where('status', 'verified')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-label-danger">
                                    <div class="card-body text-center">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="avatar-initial rounded bg-danger"><i class="bx bx-x-circle fs-4"></i></span>
                                        </div>
                                        <h5 class="card-title mb-1">Rejected</h5>
                                        <p class="mb-0 fw-bold fs-4">{{ $myRegistrations->where('status', 'rejected')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">
                        <h4 class="fw-bold mb-4">Daftar Event Tersedia</h4>

                        <h5 class="pb-1 mb-4">Event Sedang Berlangsung</h5>
                        <div class="row g-4 mb-5">
                            @forelse($ongoingEvents as $event)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 border-top border-4 border-primary">
                                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://source.unsplash.com/random/800x600/?event' }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-primary">Berlangsung</span>
                                                <span class="badge bg-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                            </div>
                                            <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                                            <p class="card-text text-muted small"><i class='bx bx-calendar'></i> {{ $event->date }} | <i class='bx bx-map'></i> {{ $event->location }}</p>
                                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                            <div class="text-muted small italic">Pendaftaran ditutup untuk event ini</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-secondary text-center">Tidak ada event yang sedang berlangsung.</div>
                                </div>
                            @endforelse
                        </div>

                        <h5 class="pb-1 mb-4">Event Mendatang</h5>
                        <div class="row g-4">
                            @forelse($upcomingEvents as $event)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://source.unsplash.com/random/800x600/?event' }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-label-secondary">Mendatang</span>
                                                <span class="badge bg-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                                <span class="badge bg-label-primary">Kuota: {{ $event->quota ?? 'Unlimited' }}</span>
                                            </div>
                                            <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                                            <p class="card-text text-muted small"><i class='bx bx-calendar'></i> {{ $event->date }} | <i class='bx bx-map'></i> {{ $event->location }}</p>
                                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                            <div class="mt-3">
                                                @if(!$event->is_registration_open)
                                                    <span class="badge bg-secondary">Pendaftaran Ditutup</span>
                                                @elseif($event->is_full)
                                                    <span class="badge bg-danger">Penuh</span>
                                                @else
                                                    @php
                                                        $isRegistered = \App\Models\Registration::where('user_id', Auth::id())->where('event_id', $event->id)->exists();
                                                    @endphp
                                                    @if($isRegistered)
                                                        <button class="btn btn-success w-100" disabled>Terdaftar</button>
                                                    @else
                                                        <button type="button" 
                                                            class="btn btn-primary w-100 register-btn" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#registrationModal"
                                                            data-event-id="{{ $event->id }}"
                                                            data-event-title="{{ $event->title }}"
                                                            data-event-type="{{ $event->type }}"
                                                            data-url="{{ route('events.register', $event->id) }}">
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

                        <!-- Registration Modal -->
                        <div class="modal fade" id="registrationModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="registrationForm" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Daftar Event</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 id="eventTitleDisplay" class="fw-bold mb-3"></h6>
                                            
                                            <div id="teamFields" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="team_name" class="form-label">Nama Tim <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="team_name" name="team_name" placeholder="Masukkan nama tim">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="substitutes" class="form-label">Cadangan (Opsional)</label>
                                                    <textarea class="form-control" id="substitutes" name="substitutes" rows="3" placeholder="Nama-nama pemain cadangan"></textarea>
                                                    <div class="form-text">Pisahkan dengan koma atau baris baru.</div>
                                                </div>
                                            </div>

                                            <p id="confirmationText">Apakah kamu yakin ingin mendaftar di event ini?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Konfirmasi Pendaftaran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <style>
                            .btn-primary { background: linear-gradient(45deg, #696cff, #8592ff); border: none; }
                            .btn-primary:hover { opacity: 0.9; }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var registrationModal = document.getElementById('registrationModal');
                                registrationModal.addEventListener('show.bs.modal', function (event) {
                                    var button = event.relatedTarget;
                                    var eventTitle = button.getAttribute('data-event-title');
                                    var eventType = button.getAttribute('data-event-type');
                                    var url = button.getAttribute('data-url');
                    
                                    var modalTitle = registrationModal.querySelector('.modal-title');
                                    var eventTitleDisplay = registrationModal.querySelector('#eventTitleDisplay');
                                    var form = registrationModal.querySelector('#registrationForm');
                                    var teamFields = registrationModal.querySelector('#teamFields');
                                    var teamNameInput = registrationModal.querySelector('#team_name');
                                    var confirmationText = registrationModal.querySelector('#confirmationText');
                    
                                    modalTitle.textContent = 'Pendaftaran ' + (eventType.charAt(0).toUpperCase() + eventType.slice(1)) + ' Event';
                                    eventTitleDisplay.textContent = eventTitle;
                                    form.setAttribute('action', url);
                    
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
