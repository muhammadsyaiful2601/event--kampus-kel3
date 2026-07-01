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
                                <h4 class="fw-bold py-3 mb-0">{{ __('messages.event_management') }}</h4>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addEventModal">
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
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="fw-semibold mb-2">{{ __('messages.registration_error') }}</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.image') }}</th>
                                            <th>{{ __('messages.event_title') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.location') }}</th>
                                            <th>{{ __('messages.quota') }}</th>
                                            <th>{{ __('messages.remaining') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.type') }}</th>
                                            <th>{{ __('messages.registration') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($events as $event)
                                            <tr>
                                                <td>
                                                    @if ($event->image)
                                                        <img src="{{ asset('storage/' . $event->image) }}"
                                                            alt="Event Image" class="rounded" width="50"
                                                            height="50" style="object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-label-secondary">{{ __('messages.no_image') }}</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $event->title }}</strong></td>
                                                <td>{{ $event->date }}</td>
                                                <td>{{ $event->location }}</td>
                                                <td>{{ $event->quota ?? 'Unlimited' }}</td>
                                                <td>{{ $event->quota ? $event->quota - $event->registrations()->count() : '-' }}
                                                </td>
                                                <td>
                                                    @if ($event->status === 'berlangsung')
                                                        <span
                                                            class="badge bg-primary">{{ __('messages.ongoing') }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary">{{ __('messages.upcoming') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-label-info">{{ ucfirst($event->type ?? 'solo') }}</span>
                                                </td>
                                                <td>
                                                    @if ($event->is_registration_open)
                                                        <span class="badge bg-success">{{ __('messages.open') }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger">{{ __('messages.closed') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editEventModal{{ $event->id }}">
                                                                <i class="bx bx-edit-alt me-1"></i>
                                                                {{ __('messages.edit') }}
                                                            </button>
                                                            <form
                                                                action="{{ route('admin.events.destroy', $event->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('{{ __('messages.delete_confirm') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger">
                                                                    <i class="bx bx-trash me-1"></i>
                                                                    {{ __('messages.delete') }}
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
                                                                        <h5 class="modal-title">
                                                                            {{ __('messages.edit') }}
                                                                            Event</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="title{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.event_title') }}</label>
                                                                                @error('title')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <input type="text"
                                                                                    id="title{{ $event->id }}"
                                                                                    name="title" class="form-control"
                                                                                    value="{{ old('title', $event->title) }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="date{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.date') }}</label>
                                                                                @error('date')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <input type="date"
                                                                                    id="date{{ $event->id }}"
                                                                                    name="date"
                                                                                    class="form-control"
                                                                                    value="{{ old('date', $event->date) }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label
                                                                                    for="location{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.location') }}</label>
                                                                                @error('location')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <input type="text"
                                                                                    id="location{{ $event->id }}"
                                                                                    name="location"
                                                                                    class="form-control"
                                                                                    value="{{ old('location', $event->location) }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="status{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.status') }}</label>
                                                                                @error('status')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <select id="status{{ $event->id }}"
                                                                                    name="status" class="form-select"
                                                                                    required>
                                                                                    <option value="berlangsung"
                                                                                        {{ old('status', $event->status) === 'berlangsung' ? 'selected' : '' }}>
                                                                                        {{ __('messages.ongoing') }}
                                                                                    </option>
                                                                                    <option value="mendatang"
                                                                                        {{ old('status', $event->status) === 'mendatang' ? 'selected' : '' }}>
                                                                                        {{ __('messages.upcoming') }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="type{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.type') }}</label>
                                                                                @error('type')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <select id="type{{ $event->id }}"
                                                                                    name="type" class="form-select"
                                                                                    required>
                                                                                    <option value="solo"
                                                                                        {{ old('type', $event->type) === 'solo' ? 'selected' : '' }}>
                                                                                        Solo</option>
                                                                                    <option value="duo"
                                                                                        {{ old('type', $event->type) === 'duo' ? 'selected' : '' }}>
                                                                                        Duo</option>
                                                                                    <option value="tim"
                                                                                        {{ old('type', $event->type) === 'tim' ? 'selected' : '' }}>
                                                                                        Tim</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label
                                                                                    for="reg_status{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.registration') }}</label>
                                                                                @error('is_registration_open')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <select
                                                                                    id="reg_status{{ $event->id }}"
                                                                                    name="is_registration_open"
                                                                                    class="form-select" required>
                                                                                    <option value="1"
                                                                                        {{ old('is_registration_open', $event->is_registration_open) ? 'selected' : '' }}>
                                                                                        {{ __('messages.open') }}
                                                                                    </option>
                                                                                    <option value="0"
                                                                                        {{ !old('is_registration_open', $event->is_registration_open) ? 'selected' : '' }}>
                                                                                        {{ __('messages.closed') }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="quota{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.quota') }}</label>
                                                                                @error('quota')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <input type="number"
                                                                                    id="quota{{ $event->id }}"
                                                                                    name="quota"
                                                                                    class="form-control"
                                                                                    value="{{ old('quota', $event->quota) }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label for="image{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.image') }}
                                                                                    ({{ __('messages.optional') }})
                                                                                </label>
                                                                                @error('image')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <input type="file"
                                                                                    id="image{{ $event->id }}"
                                                                                    name="image"
                                                                                    accept=".png,.jpg,.jpeg"
                                                                                    class="form-control">
                                                                                <small
                                                                                    class="text-muted">{{ __('messages.leave_blank_if_no_change') }}</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col mb-3">
                                                                                <label
                                                                                    for="description{{ $event->id }}"
                                                                                    class="form-label">{{ __('messages.description') }}</label>
                                                                                @error('description')
                                                                                    <div class="alert alert-danger py-2 mb-2"
                                                                                        role="alert">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                                <textarea id="description{{ $event->id }}" name="description" class="form-control" rows="3" required>{{ old('description', $event->description) }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">{{ __('messages.no_events') }}
                                                </td>
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
                                        <h5 class="modal-title">{{ __('messages.add_new_event') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="title"
                                                    class="form-label">{{ __('messages.event_title') }}</label>
                                                @error('title')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <input type="text" id="title" name="title"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="date"
                                                    class="form-label">{{ __('messages.date') }}</label>
                                                @error('date')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <input type="date" id="date" name="date"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="location"
                                                    class="form-label">{{ __('messages.location') }}</label>
                                                @error('location')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <input type="text" id="location" name="location"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="status"
                                                    class="form-label">{{ __('messages.status') }}</label>
                                                @error('status')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <select id="status" name="status" class="form-select" required>
                                                    <option value="mendatang" selected>{{ __('messages.upcoming') }}
                                                    </option>
                                                    <option value="berlangsung">{{ __('messages.ongoing') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="type"
                                                    class="form-label">{{ __('messages.type') }}</label>
                                                @error('type')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <select id="type" name="type" class="form-select" required>
                                                        <option value="solo" selected>{{ __('messages.solo') }}</option>
                                                        <option value="duo">{{ __('messages.duo') }}</option>
                                                        <option value="tim">{{ __('messages.team') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="is_registration_open"
                                                    class="form-label">{{ __('messages.registration') }}</label>
                                                @error('is_registration_open')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <select id="is_registration_open" name="is_registration_open"
                                                    class="form-select" required>
                                                    <option value="1" selected>{{ __('messages.open') }}
                                                    </option>
                                                    <option value="0">{{ __('messages.closed') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="quota"
                                                    class="form-label">{{ __('messages.quota') }}</label>
                                                @error('quota')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <input type="number" id="quota" name="quota"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="image" class="form-label">{{ __('messages.image') }}
                                                    ({{ __('messages.optional') }})</label>
                                                @error('image')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <input type="file" id="image" name="image"
                                                    accept=".png,.jpg,.jpeg" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="description"
                                                    class="form-label">{{ __('messages.description') }}</label>
                                                @error('description')
                                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                                        <button type="submit"
                                            class="btn btn-primary">{{ __('messages.save') }}</button>
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
