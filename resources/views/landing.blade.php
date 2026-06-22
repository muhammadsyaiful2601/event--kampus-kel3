<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-wide">
@include('components.header')

<head>
    <title>{{ __('messages.landing_title') }} | Welcome</title>
    <style>
        :root {
            --sneat-primary: #696cff;
            --sneat-secondary: #8592ff;
            --sneat-bg: #f5f5f9;
        }

        body {
            background-color: var(--sneat-bg);
            font-family: 'Public Sans', sans-serif !important;
            overflow-x: hidden;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar.scrolled {
            padding: 10px 0;
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: var(--sneat-primary) !important;
        }

        .hero-section {
            position: relative;
            background: linear-gradient(135deg, #696cff 0%, #30336b 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
            color: white;
            padding: 100px 0;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.1;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 35px;
            animation: fadeInUp 1s ease-out 0.2s backwards;
        }

        .hero-img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        .event-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(105, 108, 255, 0.15);
        }

        .event-img-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .event-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .event-card:hover .event-img {
            transform: scale(1.1);
        }

        .badge-type {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }

        .btn-sneat {
            background: linear-gradient(45deg, #696cff, #8592ff);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(105, 108, 255, 0.3);
        }

        .btn-sneat:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(105, 108, 255, 0.4);
            color: white;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--sneat-primary);
            border-radius: 2px;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <i class='bx bx-party fs-3 me-2'></i> {{ __('messages.landing_title') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item me-3">
                            <a class="nav-link fw-semibold text-dark"
                                href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('peserta.dashboard') }}">
                                <i class='bx bxs-dashboard me-1'></i> {{ __('messages.dashboard') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="nav-link text-dark fw-semibold"
                                href="{{ route('login') }}">{{ __('messages.login') }}</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="btn btn-sneat btn-sm" href="{{ route('register') }}">{{ __('messages.register') }}</a>
                        </li>
                    @endauth

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-dark" href="#"
                            id="langDropdown" role="button" data-bs-toggle="dropdown">
                            <i class='bx bx-globe me-1'></i> {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item py-2" href="{{ route('lang.switch', 'id') }}">Bahasa
                                    Indonesia</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('lang.switch', 'en') }}">English</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container px-4">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="hero-title">{{ __('messages.welcome') }}</h1>
                    <p class="hero-subtitle">Platform manajemen event terbaik untuk mahasiswa. Temukan, ikuti, dan
                        kelola kegiatan kampusmu dengan lebih mudah dan interaktif.</p>

                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                        class="hero-img d-none d-lg-block" alt="Hero Image">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div id="events" class="container my-5 py-5">
        <!-- Status Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class='bx bx-check-circle me-1'></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class='bx bx-error-alt me-1'></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h2 class="text-center section-title fw-bold">Event Sedang Berlangsung</h2>
        <div class="row g-4 mb-5 pb-5">
            @forelse($ongoingEvents as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 event-card shadow-sm border-0 border-top border-4 border-primary">
                        <div class="event-img-container">
                            <span class="badge bg-primary badge-type">{{ ucfirst($event->type ?? 'solo') }}</span>
                            <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' }}"
                                class="event-img" alt="{{ $event->title }}">
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-label-primary px-3 py-2 rounded-3 me-2">Berlangsung</span>
                                <small class="text-muted"><i class='bx bx-group me-1'></i>
                                    {{ $event->quota ?? 'Unlimited' }} Kuota</small>
                            </div>
                            <h5 class="card-title fw-bold mb-3">{{ $event->title }}</h5>
                            <div class="small text-muted mb-3 d-flex flex-column gap-1">
                                <span><i class='bx bx-calendar me-2'></i> {{ $event->date }}</span>
                                <span><i class='bx bx-map-pin me-2'></i> {{ $event->location }}</span>
                            </div>
                            <p class="card-text text-secondary mb-4">{{ Str::limit($event->description, 100) }}</p>
                            <div class="alert alert-light border-0 small m-0 p-2 text-center text-secondary italic">
                                <i class='bx bx-lock-alt me-1'></i> Pendaftaran ditutup
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="glass-morphism shadow-sm">
                        <i class='bx bxs-file-blank fs-1 text-muted mb-3'></i>
                        <p class="text-muted mb-0">Tidak ada event yang sedang berlangsung.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <h2 class="text-center section-title fw-bold mt-5">Event Mendatang</h2>
        <div class="row g-4">
            @forelse($upcomingEvents as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 event-card shadow-sm border-0">
                        <div class="event-img-container">
                            <span class="badge bg-info badge-type">{{ ucfirst($event->type ?? 'solo') }}</span>
                            <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' }}"
                                class="event-img" alt="{{ $event->title }}">
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-label-secondary px-3 py-2 rounded-3 me-2">Mendatang</span>
                                <small class="text-muted"><i class='bx bx-group me-1'></i>
                                    {{ $event->quota ?? 'Unlimited' }} Kuota</small>
                            </div>
                            <h5 class="card-title fw-bold mb-3">{{ $event->title }}</h5>
                            <div class="small text-muted mb-3 d-flex flex-column gap-1">
                                <span><i class='bx bx-calendar me-2'></i> {{ $event->date }}</span>
                                <span><i class='bx bx-map-pin me-2'></i> {{ $event->location }}</span>
                            </div>
                            <p class="card-text text-secondary mb-4">{{ Str::limit($event->description, 100) }}</p>
                            <div class="d-grid mt-auto">
                                @if(!$event->is_registration_open)
                                    <button class="btn btn-secondary" disabled>Pendaftaran Ditutup</button>
                                @elseif($event->is_full)
                                    <button class="btn btn-danger" disabled>Kuota Penuh</button>
                                @else
                                    @auth
                                        @if(Auth::user()->role === 'peserta')
                                            <a href="{{ route('peserta.dashboard') }}" class="btn btn-sneat">Daftar di Dashboard</a>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary" disabled
                                                title="Hanya peserta yang dapat mendaftar">Role Terbatas</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sneat">Login untuk Daftar</a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="glass-morphism shadow-sm">
                        <i class='bx bxs-calendar-x fs-1 text-muted mb-3'></i>
                        <p class="text-muted mb-0">Belum ada event mendatang yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @include('components.footer')

    <!-- Scripts -->
    @include('components.scripts')

    <script>
        // Navbar scroll effect
        window.onscroll = function () {
            var nav = document.querySelector('.navbar');
            if (window.pageYOffset > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        };
    </script>
</body>

</html>