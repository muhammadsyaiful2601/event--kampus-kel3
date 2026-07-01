<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-wide">
@include('components.header')

<head>
    <title>{{ __('messages.landing_title') }} | {{ __('messages.welcome') }}</title>
    <!-- Boxicons Link -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --sneat-primary: #696cff;
            --sneat-secondary: #787bff;
            --sneat-success: #71dd37;
            --sneat-bg: #f5f5f9;
            --sneat-card-bg: #ffffff;
            --sneat-text: #566a7f;
            --sneat-heading: #32475c;
        }

        body {
            background-color: var(--sneat-bg);
            font-family: 'Public Sans', sans-serif !important;
            color: var(--sneat-text);
            overflow-x: hidden;
        }

        /* Navbar Enhancements */
        .navbar {
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            background-color: rgba(255, 255, 255, 0.7) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1030;
        }

        .navbar.scrolled {
            padding: 12px 0;
            background-color: rgba(255, 255, 255, 0.92) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-size: 1.4rem;
            color: var(--sneat-heading) !important;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        /* Hero Section Enhanced & Secured */
        .hero-section {
            position: relative;
            background: radial-gradient(circle at 80% 20%, #787bff 0%, #4f52cc 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
            color: white;
            padding: 180px 0 140px 0;
            overflow: hidden;
        }

        /* Grid Pattern Latar Belakang */
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
            z-index: 1;
        }

        /* Ornamen Dekorasi di Lapisan Paling Bawah (z-index: 1) */
        .hero-shape {
            position: absolute;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.02) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .shape-1 {
            width: 450px;
            height: 450px;
            top: -150px;
            right: -100px;
        }

        .shape-2 {
            width: 250px;
            height: 250px;
            bottom: -80px;
            left: -80px;
        }

        /* Konten Hero di Lapisan Atas (z-index: 2) agar teks TIDAK tertutup */
        .hero-content-box {
            position: relative;
            z-index: 2;
        }

        /* Efek Teks Gradasi yang Memikat */
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 24px;
            letter-spacing: -1.5px;
            background: linear-gradient(180deg, #ffffff 30%, #e2e3ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-section {
                padding: 140px 0 100px 0;
                min-height: auto;
            }
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #eceeff;
            margin-bottom: 40px;
            line-height: 1.7;
            max-width: 720px;
            font-weight: 400;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.15s backwards;
        }

        .hero-cta {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s backwards;
        }

        /* Tombol Jelajahi yang Bersinar */
        .btn-hero-explore {
            background: #ffffff;
            color: var(--sneat-primary) !important;
            font-weight: 700;
            padding: 14px 36px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
        }

        .btn-hero-explore:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 255, 255, 0.2);
            background: #f8f9ff;
        }

        /* Interactive Filter Bar */
        .filter-wrapper {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 40px rgba(105, 108, 255, 0.06);
            margin-top: -50px;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(105, 108, 255, 0.08);
        }

        .search-input {
            border: 1px solid #d9dee3;
            padding: 12px 16px 12px 45px;
            border-radius: 10px;
            transition: all 0.3s;
            background-color: #fcfcfd;
        }

        .search-input:focus {
            border-color: var(--sneat-primary);
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.15);
            outline: none;
        }

        .search-box-container {
            position: relative;
        }

        .search-box-container i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1acb8;
        }

        .btn-filter-tab {
            border: none;
            background: #f7f8fa;
            color: var(--sneat-text);
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-filter-tab.active,
        .btn-filter-tab:hover {
            background: var(--sneat-primary);
            color: #ffffff;
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.3);
        }

        /* Modernized Cards */
        .event-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--sneat-card-bg);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
        }

        .event-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 20px 40px rgba(105, 108, 255, 0.12);
        }

        .event-img-container {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .event-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .event-card:hover .event-img {
            transform: scale(1.08);
        }

        .badge-type {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Sneat Premium Badges */
        .bg-label-primary {
            background-color: #e7e7ff !important;
            color: #696cff !important;
        }

        .bg-label-secondary {
            background-color: #ebeef0 !important;
            color: #8592a3 !important;
        }

        /* Modern Premium Buttons */
        .btn-sneat {
            background: var(--sneat-primary);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 12px rgba(105, 108, 255, 0.25);
        }

        .btn-sneat:hover {
            transform: translateY(-2px);
            background: var(--sneat-secondary);
            box-shadow: 0 6px 20px rgba(105, 108, 255, 0.35);
            color: white;
        }

        /* Keyframe Animations */
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

        .section-title {
            position: relative;
            margin-bottom: 40px;
            color: var(--sneat-heading);
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 50px;
            height: 4px;
            background: var(--sneat-primary);
            border-radius: 10px;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 1px dashed rgba(105, 108, 255, 0.2);
            border-radius: 16px;
            padding: 40px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <i class='bx bx-party fs-3 me-2 text-primary'></i> {{ __('messages.landing_title') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item me-3">
                            <a class="nav-link fw-semibold text-dark d-flex align-items-center"
                                href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('peserta.dashboard') }}">
                                <i class='bx bxs-dashboard me-1 text-primary fs-5'></i> {{ __('messages.dashboard') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="nav-link text-dark fw-semibold"
                                href="{{ route('login') }}">{{ __('messages.login') }}</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="btn btn-sneat btn-sm px-4"
                                href="{{ route('register') }}">{{ __('messages.register') }}</a>
                        </li>
                    @endauth

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-dark fw-semibold"
                            href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                            <i class='bx bx-globe me-1 text-secondary'></i> {{ strtoupper(app()->getLocale()) }}
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

    <!-- Hero Section (Clean, Secured & Premium Layout) -->
    <section class="hero-section text-center">
        <!-- Elemen dekoratif dipaksa berada di lapisan paling belakang (z-index: 1) -->
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>

        <div class="container px-4 hero-content-box">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h1 class="hero-title">{{ __('messages.welcome') }}</h1>
                    <p class="hero-subtitle mx-auto">{{ __('messages.welcome_text') }}</p>
                    <div class="hero-cta">
                        <a href="#events" class="btn btn-hero-explore shadow rounded-3">
                            <i class='bx bx-rocket me-2 animate-bounce'></i>{{ __('messages.explore_events') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div id="events" class="container my-5 py-3">

        <!-- Live Smart Interactive Search & Filter Bar -->
        <div class="filter-wrapper mb-5">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="search-box-container">
                        <i class='bx bx-search fs-4'></i>
                        <input type="text" id="eventSearch" class="form-control search-input"
                            placeholder="{{ __('messages.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-8 text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <button class="btn-filter-tab active" onclick="filterType('all', this)">{{ __('messages.all_types') }}</button>
                        <button class="btn-filter-tab" onclick="filterType('solo', this)">{{ __('messages.solo') }}</button>
                        <button class="btn-filter-tab" onclick="filterType('duo', this)">{{ __('messages.duo') }}</button>
                        <button class="btn-filter-tab" onclick="filterType('tim', this)">{{ __('messages.team') }}</button>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class='bx bx-check-circle me-1'></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class='bx bx-error-alt me-1'></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Ongoing Events Section -->
        <h2 class="text-center section-title fw-bold">{{ __('messages.ongoing_events_title') }}</h2>
        <div class="row g-4 mb-5 pb-5 event-container">
            @forelse($ongoingEvents as $event)
                <div class="col-md-6 col-lg-4 event-card-item" data-type="{{ strtolower($event->type ?? 'solo') }}"
                    data-title="{{ strtolower($event->title) }}" data-location="{{ strtolower($event->location) }}">
                    <div class="card h-100 event-card border-top border-4 border-primary">
                        <div class="event-img-container">
                            <span
                                class="badge bg-primary text-white badge-type">{{ ucfirst($event->type ?? 'solo') }}</span>
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" class="event-img"
                                    alt="{{ $event->title }}">
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <span
                                    class="badge bg-label-primary px-3 py-2 rounded-3 me-2 fw-semibold">Berlangsung</span>
                                <small class="text-muted ms-auto"><i class='bx bx-group me-1 text-primary'></i>
                                    {{ $event->quota ?? 'Unlimited' }} Kuota</small>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-3">{{ $event->title }}</h5>
                            <div class="small text-muted mb-3 d-flex flex-column gap-2">
                                <span><i class='bx bx-calendar text-primary me-2'></i> {{ $event->date }}</span>
                                <span><i class='bx bx-map-pin text-danger me-2'></i> {{ $event->location }}</span>
                            </div>
                            <p class="card-text text-secondary mb-4">{{ Str::limit($event->description, 90) }}</p>
                            <div class="alert alert-light border-0 small m-0 p-2 text-center text-secondary mt-auto">
                                <i class='bx bx-lock-alt me-1'></i> {{ __('messages.registration_closed') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center empty-state">
                    <div class="glass-morphism shadow-sm">
                        <i class='bx bxs-file-blank fs-1 text-muted mb-3'></i>
                        <p class="text-muted mb-0 fw-semibold">Tidak ada event yang sedang berlangsung.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Upcoming Events Section -->
        <h2 class="text-center section-title fw-bold mt-5">{{ __('messages.upcoming_events_title') }}</h2>
        <div class="row g-4 event-container" id="upcomingSection">
            @forelse($upcomingEvents as $event)
                <div class="col-md-6 col-lg-4 event-card-item" data-type="{{ strtolower($event->type ?? 'solo') }}"
                    data-title="{{ strtolower($event->title) }}" data-location="{{ strtolower($event->location) }}">
                    <div class="card h-100 event-card">
                        <div class="event-img-container">
                            <span
                                class="badge bg-info text-white badge-type">{{ ucfirst($event->type ?? 'solo') }}</span>
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" class="event-img"
                                    alt="{{ $event->title }}">
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <span
                                    class="badge bg-label-secondary px-3 py-2 rounded-3 me-2 fw-semibold">Mendatang</span>
                                <small class="text-muted ms-auto"><i class='bx bx-group me-1 text-info'></i>
                                    {{ $event->quota ?? 'Unlimited' }} Kuota</small>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-3">{{ $event->title }}</h5>
                            <div class="small text-muted mb-3 d-flex flex-column gap-2">
                                <span><i class='bx bx-calendar text-info me-2'></i> {{ $event->date }}</span>
                                <span><i class='bx bx-map-pin text-danger me-2'></i> {{ $event->location }}</span>
                            </div>
                            <p class="card-text text-secondary mb-4">{{ Str::limit($event->description, 90) }}</p>
                            <div class="d-grid mt-auto">
                                @if (!$event->is_registration_open)
                                    <button class="btn btn-secondary rounded-3" disabled>Pendaftaran Ditutup</button>
                                @elseif($event->is_full)
                                    <button class="btn btn-danger rounded-3" disabled>Kuota Penuh</button>
                                @else
                                    @auth
                                        @if (Auth::user()->role === 'peserta')
                                            <a href="{{ route('peserta.dashboard') }}"
                                                class="btn btn-sneat rounded-3">Daftar di Dashboard</a>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary rounded-3" disabled
                                                title="Hanya peserta yang dapat mendaftar">Role Terbatas</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sneat rounded-3">Login untuk
                                            Daftar</a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center empty-state">
                    <div class="glass-morphism shadow-sm">
                        <i class='bx bxs-calendar-x fs-1 text-muted mb-3'></i>
                        <p class="text-muted mb-0 fw-semibold">Belum ada event mendatang yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @include('components.footer')
    @include('components.scripts')

    <!-- Interactive JavaScript Logic -->
    <script>
        // Navbar dynamic scroll background
        window.addEventListener('scroll', function() {
            var nav = document.querySelector('.navbar');
            if (window.pageYOffset > 40) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Client-side Instant Filter & Search logic
        let currentType = 'all';

        function filterType(type, element) {
            document.querySelectorAll('.btn-filter-tab').forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');

            currentType = type;
            applySearchAndFilter();
        }

        document.getElementById('eventSearch').addEventListener('input', function() {
            applySearchAndFilter();
        });

        function applySearchAndFilter() {
            const searchQuery = document.getElementById('eventSearch').value.toLowerCase();
            const cards = document.querySelectorAll('.event-card-item');

            cards.forEach(card => {
                const type = card.getAttribute('data-type');
                const title = card.getAttribute('data-title');
                const location = card.getAttribute('data-location');

                const matchesType = (currentType === 'all' || type === currentType);
                const matchesSearch = (title.includes(searchQuery) || location.includes(searchQuery));

                if (matchesType && matchesSearch) {
                    card.style.display = "block";
                    card.style.animation = "fadeInUp 0.4s ease forwards";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>
