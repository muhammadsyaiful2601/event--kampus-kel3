<!DOCTYPE html>
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
                        <div class="row justify-content-center pt-5">
                            <div class="col-md-6 col-lg-5">
                                <div class="card shadow-sm">
                                    <div class="card-body p-4 p-md-5">
                                        <div class="text-center mb-4">
                                            <h3 class="fw-bold">Verifikasi Perubahan</h3>
                                            <p class="text-muted small">Kami telah mengirimkan kode OTP ke <strong>{{ $email }}</strong> untuk mengonfirmasi perubahan profil Anda.</p>
                                        </div>

                                        @if(session('status'))
                                            <div class="alert alert-success py-2 small">
                                                {{ session('status') }}
                                            </div>
                                        @endif

                                        @if(session('error'))
                                            <div class="alert alert-danger py-2 small">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <form action="{{ route('profile.otp.verify') }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="otp_code" class="form-label small fw-semibold">Kode OTP</label>
                                                <input type="text" name="otp_code" id="otp_code" 
                                                       class="form-control form-control-lg text-center fw-bold font-monospace" 
                                                       maxlength="6" placeholder="000000" required autofocus>
                                                @error('otp_code')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="d-grid mb-3">
                                                <button type="submit" class="btn btn-primary btn-lg">Verifikasi & Simpan</button>
                                            </div>
                                            
                                            <div class="text-center mt-4">
                                                <p class="small text-muted mb-0">Tidak menerima kode? 
                                                    <button type="button" id="resend-trigger-btn" class="btn btn-link p-0 text-decoration-none small" style="vertical-align: baseline;" {{ $remaining > 0 ? 'disabled' : '' }}>
                                                        Kirim Ulang
                                                    </button>
                                                    <span id="cooldown-timer" class="small text-muted {{ $remaining > 0 ? '' : 'd-none' }}">
                                                        (Tunggu <span id="timer-seconds">{{ $remaining }}</span> detik)
                                                    </span>
                                                </p>
                                            </div>
                                        </form>

                                        <form id="resend-form" action="{{ route('profile.otp.resend') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>

                                        <div class="text-center mt-3">
                                            <a href="{{ route('profile.show') }}" class="text-decoration-none small">
                                                <i class="bx bx-chevron-left"></i> Kembali ke Profil
                                            </a>
                                        </div>
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
    </div>

    @include('components.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let remaining = {{ $remaining ?? 0 }};
            const resendTriggerBtn = document.getElementById('resend-trigger-btn');
            const resendForm = document.getElementById('resend-form');
            const timerContainer = document.getElementById('cooldown-timer');
            const timerSeconds = document.getElementById('timer-seconds');

            resendTriggerBtn.addEventListener('click', function() {
                resendTriggerBtn.disabled = true;
                resendTriggerBtn.innerText = "Mengirim...";
                resendForm.submit();
            });

            function startTimer(seconds) {
                remaining = seconds;
                resendTriggerBtn.disabled = true;
                timerContainer.classList.remove('d-none');
                
                const interval = setInterval(() => {
                    remaining--;
                    if (remaining < 0) remaining = 0;
                    timerSeconds.innerText = remaining;
                    
                    if (remaining <= 0) {
                        clearInterval(interval);
                        resendTriggerBtn.disabled = false;
                        resendTriggerBtn.innerText = "Kirim Ulang";
                        timerContainer.classList.add('d-none');
                    }
                }, 1000);
            }

            if (remaining > 0) {
                startTimer(remaining);
            }
        });
    </script>
</body>
</html>
