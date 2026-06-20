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
                        <h4 class="fw-bold py-3 mb-4">{{ __('messages.registration_verification') }}</h4>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.participant') }}</th>
                                            <th>Event</th>
                                            <th>{{ __('messages.registration_date') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($registrations as $reg)
                                            <tr>
                                                <td>
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $reg->user->name }}</span>
                                                            <small class="text-muted">{{ $reg->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $reg->event->title }}</td>
                                                <td>{{ $reg->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if($reg->status === 'pending')
                                                        <span class="badge bg-label-warning">{{ __('messages.pending') }}</span>
                                                    @elseif($reg->status === 'verified')
                                                        <span class="badge bg-label-success">{{ __('messages.verified') }}</span>
                                @else
                                                        <span class="badge bg-label-danger">{{ __('messages.rejected') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($reg->status === 'pending')
                                                        <div class="d-flex gap-2">
                                                            <form action="{{ route('admin.registrations.verify', $reg->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="verified">
                                                                <button type="submit" class="btn btn-sm btn-success">{{ __('messages.verify') }}</button>
                                                            </form>
                                                            <form action="{{ route('admin.registrations.verify', $reg->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="rejected">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('messages.reject') }}</button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">{{ __('messages.finished') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">{{ __('messages.no_registrations') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
