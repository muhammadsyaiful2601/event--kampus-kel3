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
                            <div class="col-12 mb-4">
                                <div class="card">
                                <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <button type="button"
                                                class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                                aria-label="Toggle menu">
                                                <i class="bx bx-menu"></i>
                                            </button>
                                            <h4 class="fw-bold mb-0">
                                                @if(auth()->user()->role === 'admin')
                                                    {{ __('messages.admin_profile') }}
                                                @else
                                                    {{ __('messages.participant_profile') }}
                                                @endif
                                            </h4>
                                        </div>

                                        @if (session('status'))
                                            <div class="alert alert-success">{{ session('status') }}</div>
                                        @endif
                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif

                                        <form method="POST" action="{{ route('peserta.profile.sendOtp') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.name') }}</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $user->name) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.email') }}</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email', $user->email) }}" required>
                                            </div>

                                            <hr />
                                            <p class="text-muted">Untuk mengganti password, isi kolom berikut:</p>
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.current_password') }} <span class="text-danger">*</span></label>
                                                <input type="password" name="current_password" class="form-control" placeholder="{{ __('messages.enter_your_password') }}">
                                                @error('current_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.new_password') }}</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.confirm_new_password') }}</label>
                                                <input type="password" name="password_confirmation"
                                                    class="form-control">
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary rounded-pill px-4 fw-semibold" type="submit">
                                                    <i class="bx bx-send me-1"></i>{{ __('messages.send_reset_link') }}
                                                </button>
                                                <a href="{{ route('peserta.dashboard') }}"
                                                    class="btn btn-outline-secondary rounded-pill px-4">
                                                    <i class="bx bx-arrow-back me-1"></i>{{ __('messages.back_to_login') }}
                                                </a>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

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
