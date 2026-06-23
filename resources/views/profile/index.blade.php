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
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Account Settings /</span> Profile
                        </h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <h5 class="card-header">Profile Details</h5>
                                    <div class="card-body">
                                        @if(session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        @if(session('error'))
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="name" class="form-label">Full Name</label>
                                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name"
                                                        value="{{ old('name', $user->name) }}" autofocus required />
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email"
                                                        value="{{ old('email', $user->email) }}" placeholder="john.doe@example.com" required />
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                                                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="············" />
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="············" />
                                                </div>
                                            </div>
                                            <div class="mt-2 text-center text-md-start">
                                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="bx bx-info-circle me-1"></i>
                                                    Updating your profile will require OTP verification sent to your email.
                                                </small>
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
    </div>

    @include('components.scripts')
</body>
</html>
