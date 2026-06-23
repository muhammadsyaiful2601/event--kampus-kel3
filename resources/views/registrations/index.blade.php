<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

@include('components.header')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('components.sidebar')
            <div class="layout-page">
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h4 class="fw-bold py-3 mb-2">Daftar Peserta Event</h4>
                                <p class="text-muted mb-0">Lihat status pendaftaran peserta dan kelola aksi secara langsung.</p>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Nama Peserta</th>
                                                <th scope="col">Nama Event</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Tanggal Pendaftaran</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($registrations as $registration)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-semibold">
                                                                @if(Auth::user()->role === 'admin')
                                                                    {{ $registration->user->name }}
                                                                @else
                                                                    {{ Auth::user()->name }}
                                                                @endif
                                                            </span>
                                                            @if(Auth::user()->role === 'admin')
                                                                <small class="text-muted">{{ $registration->user->email }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold">{{ $registration->event->title }}</div>
                                                        <small class="text-muted">{{ $registration->event->date }} • {{ $registration->event->location }}</small>
                                                    </td>
                                                    <td>
                                                        @if($registration->status === 'pending')
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @elseif($registration->status === 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @else
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $registration->created_at->format('d M Y H:i') }}</td>
                                                    <td>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @if(Auth::user()->role === 'admin')
                                                                <form action="{{ route('admin.registrations.verify', $registration->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="diterima">
                                                                    <button type="submit" class="btn btn-sm btn-outline-success">Diterima</button>
                                                                </form>

                                                                <form action="{{ route('admin.registrations.verify', $registration->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="ditolak">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Ditolak</button>
                                                                </form>
                                                            @endif

                                                            @if(Auth::user()->role === 'admin' || Auth::id() === $registration->user_id)
                                                                <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Hapus pendaftaran ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">Belum ada pendaftaran.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
