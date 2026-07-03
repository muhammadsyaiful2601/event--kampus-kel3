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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button"
                                    class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                    aria-label="Toggle menu">
                                    <i class="bx bx-menu"></i>
                                </button>
                                <h4 class="fw-bold py-3 mb-0">{{ __('messages.admin_management') }}</h4>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addAdminModal">
                                {{ __('messages.add_event') }}
                            </button>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.email') }}</th>
                                            <th>{{ __('messages.role') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($admins as $admin)
                                            <tr>
                                                <td><strong>{{ $admin->name }}</strong></td>
                                                <td>{{ $admin->email }}</td>
                                                <td><span
                                                        class="badge bg-label-primary">{{ ucfirst($admin->role) }}</span>
                                                </td>
                                                <td>
                                                    @if (auth()->id() === $admin->id)
                                                        <form action="{{ route('admin.admins.destroy', $admin->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('{{ __('messages.delete_own_account_confirm') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted small">{{ __('messages.confirm_delete_other_admin') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Add Admin -->
                    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.admins.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('messages.add_event') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="name" class="form-label">{{ __('messages.name') }}</label>
                                                <input type="text" id="name" name="name" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                                <input type="email" id="email" name="email" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control" required minlength="8">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" class="form-control" required
                                                    minlength="8">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                                    </div>
                                </form>
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
