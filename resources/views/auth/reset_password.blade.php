<!DOCTYPE html>
<html lang="en">
@include('components.header')

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-5">
                <div class="card shadow border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">{{ __('messages.reset_password_title') }}</h3>
                            <p class="text-muted">{{ __('messages.reset_password_description') }}</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.reset.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                                @error('password')
                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('messages.confirm_new_password') }}</label>
                                @error('password_confirmation')
                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">{{ __('messages.reset_password_button') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.scripts')
</body>

</html>
