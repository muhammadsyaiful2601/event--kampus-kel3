<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Dashboard</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item">
            <div class="dropdown">
                <a class="menu-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="menu-icon tf-icons bx bx-globe"></i>
                    <div>{{ strtoupper(app()->getLocale()) }}</div>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                            href="{{ route('lang.switch', 'id') }}">{{ __('messages.indonesian') }}</a></li>
                    <li><a class="dropdown-item"
                            href="{{ route('lang.switch', 'en') }}">{{ __('messages.english') }}</a></li>
                </ul>
            </div>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.dashboard', 'peserta.dashboard') ? 'active' : '' }}">
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('peserta.dashboard') }}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">{{ __('messages.dashboard') }}</div>
            </a>
        </li>

        @if (Auth::user()->role === 'admin')
            <li class="menu-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <a href="{{ route('admin.events.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                    <div data-i18n="Layouts">{{ __('messages.event_management') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                <a href="{{ route('admin.registrations.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-check"></i>
                    <div data-i18n="Layouts">{{ __('messages.registration_verification') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <a href="{{ route('admin.admins.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-check"></i>
                    <div data-i18n="Layouts">{{ __('messages.admin_management') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <a href="{{ route('admin.logs.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div>{{ __('messages.activity_log') }}</div>
                </a>
            </li>
        @endif

        @if (Auth::user()->role !== 'admin')
            <li class="menu-item {{ request()->routeIs('pendaftaran.index') ? 'active' : '' }}">
                <a href="{{ route('pendaftaran.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                    <div>{{ __('messages.participant_event_list') }}</div>
                </a>
            </li>
        @endif

        <li class="menu-item {{ request()->routeIs('peserta.profile*') ? 'active' : '' }}">
            <a href="{{ route('peserta.profile') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>{{ __('messages.profile') }}</div>
            </a>
        </li>

        <li class="menu-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="javascript:void(0);" class="menu-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div>{{ __('messages.logout') }}</div>
            </a>
        </li>

    </ul>
</aside>
