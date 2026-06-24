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

                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="fw-bold mb-3">Verifikasi OTP</h4>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                                        @endif

                                        <form method="POST" action="{{ route('peserta.profile.verify.post') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Kode OTP</label>
                                                <input type="text" name="otp" class="form-control" required>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary" type="submit">Verifikasi &
                                                    Terapkan</button>
                                                <a href="{{ route('peserta.profile') }}"
                                                    class="btn btn-outline-secondary">Batal</a>
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
