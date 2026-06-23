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
                        <h4 class="fw-bold py-3 mb-4">Pendaftaran Event</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title }}</h5>
                                <p class="text-muted">{{ $event->date }} | {{ $event->location }}</p>
                                <p>{{ $event->description }}</p>

                                <form action="{{ route('events.register', $event->id) }}" method="POST">
                                    @csrf

                                    @if ($event->type === 'tim')
                                        <div class="mb-3">
                                            <label for="team_name" class="form-label">Nama Tim</label>
                                            <input type="text" class="form-control" id="team_name" name="team_name" value="{{ old('team_name') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="substitutes" class="form-label">Cadangan (Opsional)</label>
                                            <textarea class="form-control" id="substitutes" name="substitutes" rows="3">{{ old('substitutes') }}</textarea>
                                        </div>
                                    @else
                                        <p class="text-muted">Isi form di bawah untuk menyelesaikan pendaftaran.</p>
                                    @endif

                                    <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
                                    <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary">Kembali</a>
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
