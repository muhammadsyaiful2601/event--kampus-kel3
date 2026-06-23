<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Sneat</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
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
        @endif
        <li class="menu-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
            <a href="{{ route('profile.show') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div>Profile</div>
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