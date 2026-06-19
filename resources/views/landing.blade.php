<!DOCTYPE html>
<html lang="en">
@include('components.header')
<head>
    <title>Event Kampus - Landing Page</title>
    <style>
        .hero-section {
            background: linear_gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://source.unsplash.com/random/1600x900/?campus,event');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .event-card {
            transition: transform 0.3s;
        }
        .event-card:hover {
            transform: translateY(-10px);
        }
        .btn-premium {
            background: linear-gradient(45deg, #696cff, #8592ff);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Event Kampus</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('peserta.dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold">Temukan Event Kampus Seru!</h1>
            <p class="lead">Jelajahi berbagai kegiatan menarik dan tingkatkan skill kamu di sini.</p>
        </div>
    </div>

    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="text-center mb-5 fw-bold">Event Sedang Berlangsung</h2>
        <div class="row g-4 mb-5">
            @forelse($ongoingEvents as $event)
                <div class="col-md-4">
                    <div class="card h-100 event-card shadow-sm border-0 border-top border-4 border-primary">
                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://source.unsplash.com/random/800x600/?event' }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-primary">Berlangsung</span>
                                <span class="badge bg-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                <span class="badge bg-label-primary">Kuota: {{ $event->quota ?? 'Unlimited' }}</span>
                            </div>
                            <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                            <p class="card-text text-muted small"><i class='bx bx-calendar'></i> {{ $event->date }} | <i class='bx bx-map'></i> {{ $event->location }}</p>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small italic">Pendaftaran ditutup untuk event yang sedang berlangsung</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Tidak ada event yang sedang berlangsung.</p>
                </div>
            @endforelse
        </div>

        <h2 class="text-center mb-5 fw-bold">Event Mendatang</h2>
        <div class="row g-4">
            @forelse($upcomingEvents as $event)
                <div class="col-md-4">
                    <div class="card h-100 event-card shadow-sm border-0">
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
                            <div class="d-flex justify-content-between align-items-center">
                                @if(!$event->is_registration_open)
                                    <span class="badge bg-secondary">Pendaftaran Ditutup</span>
                                @elseif($event->is_full)
                                    <span class="badge bg-danger">Penuh</span>
                                @else
                                    @auth
                                        <button type="button" 
                                            class="btn btn-premium register-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#registrationModal"
                                            data-event-id="{{ $event->id }}"
                                            data-event-title="{{ $event->title }}"
                                            data-event-type="{{ $event->type }}"
                                            data-url="{{ route('events.register', $event->id) }}">
                                            Daftar
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-premium">Login untuk Daftar</a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada event mendatang.</p>
                </div>
            @endforelse
        </div>
    </div>

    @include('components.footer')

    <!-- Registration Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="registrationForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalLabel">Daftar Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="eventTitleDisplay" class="fw-bold mb-3"></p>
                        
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-premium">Konfirmasi Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.scripts')
    
    <script>
        $(document).ready(function() {
            var registrationModal = document.getElementById('registrationModal');
            registrationModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var eventId = button.getAttribute('data-event-id');
                var eventTitle = button.getAttribute('data-event-title');
                var eventType = button.getAttribute('data-event-type');
                var url = button.getAttribute('data-url');

                var modalTitle = registrationModal.querySelector('.modal-title');
                var eventTitleDisplay = registrationModal.querySelector('#eventTitleDisplay');
                var form = registrationModal.querySelector('#registrationForm');
                var teamFields = registrationModal.querySelector('#teamFields');
                var teamNameInput = registrationModal.querySelector('#team_name');
                var confirmationText = registrationModal.querySelector('#confirmationText');

                modalTitle.textContent = 'Daftar ' + (eventType.charAt(0).toUpperCase() + eventType.slice(1)) + ' Event';
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
</body>
</html>
