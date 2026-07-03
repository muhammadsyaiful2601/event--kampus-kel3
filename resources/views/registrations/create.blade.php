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
                        <!-- Header -->
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <button type="button"
                                class="btn btn-primary btn-icon layout-menu-toggle d-inline-flex d-xl-none"
                                aria-label="Toggle menu">
                                <i class="bx bx-menu"></i>
                            </button>
                            <div>
                                <h4 class="fw-bold py-3 mb-2">{{ __('messages.registration_title') }}</h4>
                                <p class="text-muted">{{ __('messages.registration_description') }}</p>
                            </div>
                        </div>

                        <!-- Alert Errors -->
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

                        <!-- Form Card -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary bg-gradient">
                                <h5 class="mb-0 text-white">{{ __('messages.registration_title_modal') }}</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pendaftaran.store') }}" method="POST" id="registrationForm"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <!-- Event Selection -->
                                    <div class="mb-4">
                                        <label for="event_id" class="form-label fw-semibold">{{ __('messages.select_event') }} <span
                                                class="text-danger">{{ __('messages.required_field') }}</span></label>
                                        <div id="event_id_error" class="text-danger small mb-2" style="display: none;"></div>
                                        @error('event_id')
                                            <div class="alert alert-danger py-2 mb-2" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <select
                                            class="form-select form-select-lg @error('event_id') is-invalid @enderror"
                                            id="event_id" name="event_id" required onchange="updateEventInfo()">
                                            <option value="">-- Pilih Event --</option>
                                            @forelse($events as $event)
                                                <option value="{{ $event->id }}" data-date="{{ $event->date }}"
                                                    data-location="{{ $event->location }}"
                                                    data-description="{{ $event->description }}"
                                                    data-quota="{{ $event->quota }}"
                                                    data-registered="{{ $event->registrations()->count() }}"
                                                    data-type="{{ $event->type }}"
                                                    {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->title }} • {{ $event->date }}
                                                </option>
                                            @empty
                                                <option value="" disabled>{{ __('messages.no_events') }}</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <!-- Event Info Display -->
                                    <div id="eventInfo" style="display: none;" class="mb-4">
                                        <div class="alert alert-light border">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">{{ __('messages.date_label') }}</div>
                                                    <div class="fw-semibold" id="infoDate"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">{{ __('messages.location_label') }}</div>
                                                    <div class="fw-semibold" id="infoLocation"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">{{ __('messages.description_label') }}</div>
                                                    <div class="fw-semibold small" id="infoDescription"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="text-muted small">{{ __('messages.quota_label') }}</div>
                                                    <div class="fw-semibold"><span id="infoRegistered">0</span>/<span
                                                            id="infoQuota">-</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Participant Details -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label for="participant_name" class="form-label fw-semibold">{{ __('messages.participant_name') }}
                                                <span class="text-danger">{{ __('messages.required_field') }}</span></label>
                                            <div id="participant_name_error" class="text-danger small mb-2" style="display: none;"></div>
                                            @error('participant_name')
                                                <div class="alert alert-danger py-2 mb-2" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="text" id="participant_name" name="participant_name"
                                                value="{{ old('participant_name') }}"
                                                class="form-control @error('participant_name') is-invalid @enderror"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="department" class="form-label fw-semibold">{{ __('messages.department') }} <span
                                                    class="text-danger">{{ __('messages.required_field') }}</span></label>
                                            <div id="department_error" class="text-danger small mb-2" style="display: none;"></div>
                                            @error('department')
                                                <div class="alert alert-danger py-2 mb-2" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="text" id="department" name="department"
                                                value="{{ old('department') }}"
                                                class="form-control @error('department') is-invalid @enderror" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="year" class="form-label fw-semibold">{{ __('messages.year') }} <span
                                                    class="text-danger">{{ __('messages.required_field') }}</span></label>
                                            <div id="year_error" class="text-danger small mb-2" style="display: none;"></div>
                                            @error('year')
                                                <div class="alert alert-danger py-2 mb-2" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="text" id="year" name="year"
                                                value="{{ old('year') }}"
                                                class="form-control @error('year') is-invalid @enderror" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="age" class="form-label fw-semibold">{{ __('messages.age') }} <span
                                                    class="text-danger">{{ __('messages.required_field') }}</span></label>
                                            <div id="age_error" class="text-danger small mb-2" style="display: none;"></div>
                                            @error('age')
                                                <div class="alert alert-danger py-2 mb-2" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="number" id="age" name="age"
                                                value="{{ old('age') }}"
                                                class="form-control @error('age') is-invalid @enderror" min="10"
                                                max="120" required>
                                        </div>
                                        <div class="col-md-12" id="teamFields" style="display: none;">
                                            <label for="team_name" class="form-label fw-semibold">{{ __('messages.team_name') }}</label>
                                            <div id="team_name_error" class="text-danger small mb-2" style="display: none;"></div>
                                            @error('team_name')
                                                <div class="alert alert-danger py-2 mb-2" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="text" id="team_name" name="team_name"
                                                value="{{ old('team_name') }}"
                                                class="form-control @error('team_name') is-invalid @enderror">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="participant_photo" class="form-label fw-semibold">{{ __('messages.participant_photo') }}
                                                <span class="text-danger">{{ __('messages.required_field') }}</span></label>
                                            <div id="participant_photo_error" class="text-danger small mb-2" style="display: none;"></div>
                                            @error('participant_photo')
                                                <div class="alert alert-danger py-2 mb-2" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="file" id="participant_photo" name="participant_photo"
                                                class="form-control @error('participant_photo') is-invalid @enderror"
                                                accept=".png,.jpg,.jpeg" required>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bx bx-check me-1"></i>{{ __('messages.register_now') }}
                                        </button>
                                        <a href="{{ route('pendaftaran.index') }}"
                                            class="btn btn-outline-secondary btn-lg">
                                            <i class="bx bx-arrow-back me-1"></i>{{ __('messages.back') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">{{ __('messages.important_info') }}</h6>
                                <ul class="mb-0 ps-3">
                                    <li class="mb-2">{{ __('messages.read_description') }}</li>
                                    <li class="mb-2">{{ __('messages.pending_status') }}</li>
                                    <li class="mb-2">{{ __('messages.admin_verification') }}</li>
                                    <li>{{ __('messages.one_registration') }}</li>
                                </ul>
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

    <script>
        function showError(fieldId, message) {
            const errorDiv = document.getElementById(fieldId + '_error');
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            }
        }

        function clearError(fieldId) {
            const errorDiv = document.getElementById(fieldId + '_error');
            if (errorDiv) {
                errorDiv.style.display = 'none';
                errorDiv.textContent = '';
            }
        }

        function validateField(field, fieldId, errorMessage) {
            if (!field.value.trim()) {
                showError(fieldId, errorMessage);
                return false;
            } else {
                clearError(fieldId);
                return true;
            }
        }

        function validateAge() {
            const ageField = document.getElementById('age');
            const age = parseInt(ageField.value);
            if (ageField.value && (isNaN(age) || age < 10 || age > 120)) {
                showError('age', '{{ __('messages.age_range') }}');
                return false;
            } else {
                clearError('age');
                return true;
            }
        }

        // Add event listeners for real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const eventId = document.getElementById('event_id');
            const participantName = document.getElementById('participant_name');
            const department = document.getElementById('department');
            const year = document.getElementById('year');
            const age = document.getElementById('age');
            const participantPhoto = document.getElementById('participant_photo');
            const teamName = document.getElementById('team_name');

            // Event ID validation
            eventId.addEventListener('change', function() {
                if (this.value === '') {
                    showError('event_id', '{{ __('messages.select_event_required') }}');
                } else {
                    clearError('event_id');
                }
            });

            // Participant name validation
            participantName.addEventListener('blur', function() {
                validateField(this, 'participant_name', '{{ __('messages.participant_name_required') }}');
            });

            // Department validation
            department.addEventListener('blur', function() {
                validateField(this, 'department', '{{ __('messages.department_required') }}');
            });

            // Year validation
            year.addEventListener('blur', function() {
                validateField(this, 'year', '{{ __('messages.year_required') }}');
            });

            // Age validation
            age.addEventListener('blur', function() {
                validateAge();
            });

            // Team name validation (only for team events)
            if (teamName) {
                teamName.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        showError('team_name', '{{ __('messages.team_name_required') }}');
                    } else {
                        clearError('team_name');
                    }
                });
            }

            // Photo validation
            participantPhoto.addEventListener('change', function() {
                if (this.files.length > 0) {
                    clearError('participant_photo');
                }
            });

            // Form submission validation
            document.getElementById('registrationForm').addEventListener('submit', function(e) {
                let isValid = true;

                isValid = validateField(eventId, 'event_id', '{{ __('messages.select_event_required') }}') && isValid;
                isValid = validateField(participantName, 'participant_name', '{{ __('messages.participant_name_required') }}') && isValid;
                isValid = validateField(department, 'department', '{{ __('messages.department_required') }}') && isValid;
                isValid = validateField(year, 'year', '{{ __('messages.year_required') }}') && isValid;
                isValid = validateAge() && isValid;

                if (teamName && teamName.hasAttribute('required') && !teamName.value.trim()) {
                    showError('team_name', '{{ __('messages.team_name_required') }}');
                    isValid = false;
                }

                if (!participantPhoto.files.length) {
                    showError('participant_photo', '{{ __('messages.participant_photo') }} {{ __('messages.field_required', {"field": "Foto Peserta"}) }}');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Initialize
            updateEventInfo();
        });

        function updateEventInfo() {
            const select = document.getElementById('event_id');
            const selectedOption = select.options[select.selectedIndex];
            const infoDiv = document.getElementById('eventInfo');
            const teamFields = document.getElementById('teamFields');

            if (selectedOption && selectedOption.value) {
                document.getElementById('infoDate').textContent = selectedOption.dataset.date;
                document.getElementById('infoLocation').textContent = selectedOption.dataset.location;
                document.getElementById('infoDescription').textContent = selectedOption.dataset.description;
                document.getElementById('infoQuota').textContent = selectedOption.dataset.quota || 'Unlimited';
                document.getElementById('infoRegistered').textContent = selectedOption.dataset.registered;
                infoDiv.style.display = 'block';

                if (selectedOption.dataset.type === 'tim') {
                    teamFields.style.display = 'block';
                    document.getElementById('team_name').setAttribute('required', 'required');
                } else {
                    teamFields.style.display = 'none';
                    document.getElementById('team_name').removeAttribute('required');
                }
            } else {
                infoDiv.style.display = 'none';
                teamFields.style.display = 'none';
                document.getElementById('team_name').removeAttribute('required');
            }
        }
    </script>
</body>
</html>