<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Sneat</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('admin.dashboard', 'peserta.dashboard') ? 'active' : '' }}">
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('peserta.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @if(Auth::user()->role === 'admin')
        <li class="menu-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <a href="{{ route('admin.events.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                <div data-i18n="Layouts">Manajemen Event</div>
            </a>
        </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('landing') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-globe"></i>
                <div>Landing Page</div>
            </a>
        </li>
    </ul>
</aside>
