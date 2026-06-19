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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addEventModal">
                                Tambah Event
                            </button>
                        </div>

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Gambar</th>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                            <th>Lokasi</th>
                                            <th>Kuota</th>
                                            <th>Sisa</th>
                                            <th>Status</th>
                                            <th>Tipe</th>
                                            <th>Pendaftaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($events as $event)
                                            <tr>
                                                <td>
                                                    @if($event->image)
                                                        <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image"
                                                            class="rounded" width="50" height="50" style="object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-label-secondary">No Image</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $event->title }}</strong></td>
                                                <td>{{ $event->date }}</td>
                                                <td>{{ $event->location }}</td>
                                                <td>{{ $event->quota ?? 'Unlimited' }}</td>
                                                <td>{{ $event->quota ? $event->quota - $event->registrations()->count() : '-' }}
                                                </td>
                                                <td>
                                                    @if($event->status === 'berlangsung')
                                                        <span class="badge bg-primary">Berlangsung</span>
                                                    @else
                                                        <span class="badge bg-secondary">Mendatang</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                                </td>
                                                <td>
                                                    @if($event->is_registration_open)
                                                        <span class="badge bg-success">Terbuka</span>
                                                    @else
                                                        <span class="badge bg-danger">Tertutup</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editEventModal{{ $event->id }}">
                                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                                            </button>
                                                            <form action="{{ route('admin.events.destroy', $event->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bx bx-trash me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Edit Event -->
                                                    <div class="modal fade" id="editEventModal{{ $event->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('admin.events.update', $event->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Event</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="title{{ $event->id }}"
                                                                                    class="form-label">Judul Event</label>
                                                                                <input type="text"
                                                                                    id="title{{ $event->id }}" name="title"
                                                                                    class="form-control"
                                                                                    value="{{ $event->title }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="date{{ $event->id }}"
                                                                                    class="form-label">Tanggal</label>
                                                                                <input type="date" id="date{{ $event->id }}"
                                                                                    name="date" class="form-control"
                                                                                    value="{{ $event->date }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="location{{ $event->id }}"
                                                                                    class="form-label">Lokasi</label>
                                                                                <input type="text"
                                                                                    id="location{{ $event->id }}"
                                                                                    name="location" class="form-control"
                                                                                    value="{{ $event->location }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="status{{ $event->id }}"
                                                                                    class="form-label">Status</label>
                                                                                <select id="status{{ $event->id }}"
                                                                                    name="status" class="form-select"
                                                                                    required>
                                                                                    <option value="berlangsung" {{ $event->status === 'berlangsung' ? 'selected' : '' }}>Sedang
                                                                                        Berlangsung</option>
                                                                                    <option value="mendatang" {{ $event->status === 'mendatang' ? 'selected' : '' }}>Akan Datang
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="type{{ $event->id }}" class="form-label">Tipe Event</label>
                                                                                <select id="type{{ $event->id }}" name="type" class="form-select" required>
                                                                                    <option value="solo" {{ $event->type === 'solo' ? 'selected' : '' }}>Solo</option>
                                                                                    <option value="duo" {{ $event->type === 'duo' ? 'selected' : '' }}>Duo</option>
                                                                                    <option value="tim" {{ $event->type === 'tim' ? 'selected' : '' }}>Tim</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="reg_status{{ $event->id }}" class="form-label">Status Pendaftaran</label>
                                                                                <select id="reg_status{{ $event->id }}" name="is_registration_open" class="form-select" required>
                                                                                    <option value="1" {{ $event->is_registration_open ? 'selected' : '' }}>Buka</option>
                                                                                    <option value="0" {{ !$event->is_registration_open ? 'selected' : '' }}>Tutup</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="quota{{ $event->id }}"
                                                                                    class="form-label">Kuota</label>
                                                                                <input type="number"
                                                                                    id="quota{{ $event->id }}" name="quota"
                                                                                    class="form-control"
                                                                                    value="{{ $event->quota }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="image{{ $event->id }}"
                                                                                    class="form-label">Foto
                                                                                    (Opsional)</label>
                                                                                <input type="file"
                                                                                    id="image{{ $event->id }}" name="image"
                                                                                    class="form-control">
                                                                                <small class="text-muted">Biarkan kosong
                                                                                    jika tidak ingin mengubah foto.</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="description{{ $event->id }}"
                                                                                    class="form-label">Deskripsi</label>
                                                                                <textarea id="description{{ $event->id }}"
                                                                                    name="description" class="form-control"
                                                                                    rows="3"
                                                                                    required>{{ $event->description }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan
                                                                            Perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Belum ada event.</td>
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
                                <form action="{{ route('admin.events.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Event Baru</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="title" class="form-label">Judul Event</label>
                                                <input type="text" id="title" name="title" class="form-control"
                                                    required>
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
                                                <input type="text" id="location" name="location" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select id="status" name="status" class="form-select" required>
                                                    <option value="mendatang" selected>Akan Datang</option>
                                                    <option value="berlangsung">Sedang Berlangsung</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="type" class="form-label">Tipe Event</label>
                                                <select id="type" name="type" class="form-select" required>
                                                    <option value="solo" selected>Solo</option>
                                                    <option value="duo">Duo</option>
                                                    <option value="tim">Tim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="is_registration_open" class="form-label">Status Pendaftaran</label>
                                                <select id="is_registration_open" name="is_registration_open" class="form-select" required>
                                                    <option value="1" selected>Buka</option>
                                                    <option value="0">Tutup</option>
                                                </select>
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
                                                <label for="image" class="form-label">Foto (Opsional)</label>
                                                <input type="file" id="image" name="image" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="description" class="form-label">Deskripsi</label>
                                                <textarea id="description" name="description" class="form-control"
                                                    rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Batal</button>
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