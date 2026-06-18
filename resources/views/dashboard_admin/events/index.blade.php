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
                            <h4 class="fw-bold py-3 mb-0">Manajemen Event</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                Tambah Event
                            </button>
                        </div>

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                            <th>Lokasi</th>
                                            <th>Kuota</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($events as $event)
                                            <tr>
                                                <td><strong>{{ $event->title }}</strong></td>
                                                <td>{{ $event->date }}</td>
                                                <td>{{ $event->location }}</td>
                                                <td>{{ $event->quota ?? 'Unlimited' }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-menu" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                                            <a class="dropdown-menu" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada event.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Add Event -->
                    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('admin.events.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Event Baru</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="title" class="form-label">Judul Event</label>
                                                <input type="text" id="title" name="title" class="form-control" required px="Event name">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="date" class="form-label">Tanggal</label>
                                                <input type="date" id="date" name="date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="location" class="form-label">Lokasi</label>
                                                <input type="text" id="location" name="location" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="quota" class="form-label">Kuota</label>
                                                <input type="number" id="quota" name="quota" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="description" class="form-label">Deskripsi</label>
                                                <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
    </div>
    @include('components.scripts')
</body>
</html>
