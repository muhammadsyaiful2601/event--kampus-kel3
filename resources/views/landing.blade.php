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
        <h2 class="text-center mb-5 fw-bold">Event Mendatang</h2>
        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-md-4">
                    <div class="card h-100 event-card shadow-sm border-0">
                        <img src="{{ $event->image ?? 'https://source.unsplash.com/random/800x600/?event' }}" class="card-img-top" alt="{{ $event->title }}">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                            <p class="card-text text-muted small"><i class='bx bx-calendar'></i> {{ $event->date }} | <i class='bx bx-map'></i> {{ $event->location }}</p>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-label-primary">Kuota: {{ $event->quota ?? 'Unlimited' }}</span>
                                @auth
                                    <form action="{{ route('events.register', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-premium">Daftar</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-premium">Login untuk Daftar</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada event tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

    @include('components.footer')
    @include('components.scripts')
</body>
</html>
