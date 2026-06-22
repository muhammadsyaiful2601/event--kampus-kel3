<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.header')
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-5">
                <div class="card shadow border-0">
                    <div class="card-body p-5">
                        <div class="text-end mb-2">
                            <a href="{{ route('lang.switch', app()->getLocale() == 'en' ? 'id' : 'en') }}" class="text-decoration-none small">
                                <i class='bx bx-globe'></i> {{ app()->getLocale() == 'en' ? 'Indonesian' : 'English' }}
                            </a>
                        </div>
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">{{ __('messages.verify_otp_reset') }}</h3>
                            <p class="text-muted">{{ __('messages.otp_message') }}</p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('password.reset.verify') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="otp_code" class="form-label">{{ __('messages.otp_code') }}</label>
                                <input type="text" name="otp_code" id="otp_code" class="form-control form-control-lg text-center fw-bold" maxlength="6" placeholder="123456" required>
                                @error('otp_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">{{ __('messages.verify') }}</button>
                            </div>
                            <div class="text-center">
                                <p class="small text-muted mb-0">{{ __('messages.not_receiving_code') }} 
                                    <button type="button" id="resend-trigger-btn" class="btn btn-link p-0 text-decoration-none small" style="vertical-align: baseline;" {{ $remaining > 0 ? 'disabled' : '' }}>
                                        {{ __('messages.resend') }}
                                    </button>
                                    <span id="cooldown-timer" class="small text-muted {{ $remaining > 0 ? '' : 'd-none' }}">
                                        ({{ __('messages.wait') }} <span id="timer-seconds">{{ $remaining }}</span>s)
                                    </span>
                                </p>
                            </div>
                        </form>

                        <form id="resend-form" action="{{ route('password.reset.otp.resend') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
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
                resendTriggerBtn.innerText = "{{ __('messages.sending') }}";
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
                        resendTriggerBtn.innerText = "{{ __('messages.resend') }}";
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
