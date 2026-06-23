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
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h4 class="fw-bold py-3 mb-2">
                                    <i class="bx bx-qr-code me-2"></i>Verifikasi Peserta dengan QR Code
                                </h4>
                                <p class="text-muted mb-0">Scan QR ticket peserta untuk memverifikasi kehadiran menggunakan kamera atau unggah gambar.</p>
                            </div>
                            <a href="{{ route('admin.registrations.index') }}"
                                class="btn btn-outline-secondary btn-lg mt-3 mt-md-0">
                                <i class="bx bx-arrow-back me-1"></i>Kembali ke Daftar
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-5 mb-4">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                                        <h5 class="card-title mb-0"><i class="bx bx-camera me-2"></i>Kamera Scanner</h5>
                                        <select id="cameraSelect" class="form-select form-select-sm w-auto" style="max-width: 200px;">
                                            <option value="">Memuat kamera...</option>
                                        </select>
                                    </div>
                                    <div class="card-body">
                                        <div class="position-relative bg-light rounded-3 overflow-hidden border mb-3" style="min-height: 280px;">
                                            <div id="reader" style="width: 100%;"></div>
                                            <div id="scannerOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-70 text-white z-3">
                                                <i class="bx bx-camera-off fs-1 mb-2"></i>
                                                <p class="mb-0 small fw-semibold">Kamera Belum Diaktifkan</p>
                                                <button id="startCamBtn" class="btn btn-primary btn-sm mt-3"><i class="bx bx-play me-1"></i>Mulai Scan</button>
                                            </div>
                                            <div id="laserLine" class="position-absolute start-0 w-100 bg-danger opacity-75 d-none z-2" style="height: 3px; box-shadow: 0 0 8px #dc3545; animation: scanAnim 2s infinite linear;"></div>
                                        </div>
                                        <button id="stopCamBtn" class="btn btn-outline-danger btn-sm w-100 d-none"><i class="bx bx-stop me-1"></i>Hentikan Kamera</button>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs nav-fill mb-3" id="methodTabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active py-2" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual-panel" type="button" role="tab"><i class="bx bx-edit-alt me-1"></i>Input Manual</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link py-2" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-panel" type="button" role="tab"><i class="bx bx-upload me-1"></i>Unggah QR</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content p-0 border-0" id="methodTabsContent">
                                            <div class="tab-pane fade show active" id="manual-panel" role="tabpanel">
                                                <label class="form-label small fw-semibold text-muted">Masukkan Kode Tiket Peserta</label>
                                                <div class="input-group">
                                                    <input type="text" id="manualCodeInput" class="form-control text-uppercase" placeholder="Contoh: ABCDE12345" autocomplete="off">
                                                    <button class="btn btn-info" type="button" id="manualVerifyBtn"><i class="bx bx-check-shield me-1"></i>Verifikasi</button>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="file-panel" role="tabpanel">
                                                <label class="form-label small fw-semibold text-muted">Pilih Gambar/Screenshot QR Code</label>
                                                <input type="file" id="qrFileInput" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div id="resultContainer" class="mb-4">
                                    <div class="card h-100 min-vh-25 d-flex align-items-center justify-content-center p-5 text-center bg-light border-dashed">
                                        <div class="text-muted">
                                            <i class="bx bx-scan display-4 mb-3 text-secondary opacity-50"></i>
                                            <h5>Menunggu Pemindaian</h5>
                                            <p class="mb-0 small max-w-350">Silakan arahkan kode QR tiket ke kamera, masukkan kode secara manual, atau unggah file gambar tiket untuk divalidasi.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                                        <h5 class="card-title mb-0"><i class="bx bx-list-check me-2 text-primary"></i>Log Verifikasi Hari Ini</h5>
                                        <span class="badge bg-label-secondary rounded-pill small fw-semibold">10 Terakhir</span>
                                    </div>
                                    <div class="table-responsive text-nowrap" style="max-height: 400px;">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light position-sticky top-0 z-1">
                                                <tr>
                                                    <th>Waktu</th>
                                                    <th>Nama Peserta</th>
                                                    <th>Kode Tiket</th>
                                                    <th>Nama Event</th>
                                                    <th>Petugas</th>
                                                </tr>
                                            </thead>
                                            <tbody id="logTableBody">
                                                @forelse($recentScans as $scan)
                                                    <tr style="animation: fadeSlideIn 0.3s ease-out both;">
                                                        <td><small class="text-muted fw-semibold">{{ $scan->verified_at ? $scan->verified_at->format('H:i:s') : '-' }}</small></td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-semibold text-dark">{{ $scan->participant_name ?? ($scan->user->name ?? '-') }}</span>
                                                                <small class="text-muted text-truncate" style="max-width: 150px;">{{ $scan->department ?? '-' }}</small>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-label-primary font-monospace">{{ $scan->ticket_code }}</span></td>
                                                        <td><span class="text-truncate d-inline-block" style="max-width: 180px;">{{ $scan->event->title ?? '-' }}</span></td>
                                                        <td><small class="badge bg-label-secondary">{{ $scan->verified_by ?? 'System' }}</small></td>
                                                    </tr>
                                                @empty
                                                    <tr id="emptyRow">
                                                        <td colspan="5" class="text-center py-4 text-muted">
                                                            <i class="bx bx-info-circle mb-1"></i> Belum ada aktivitas verifikasi tiket hari ini.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.scripts')

    {{-- Elemen Tersembunyi untuk Keperluan Scanner Gambar File QR --}}
    <div id="fileScannerTmp" style="width: 1px; height: 1px; opacity: 0; position: absolute; pointer-events: none;"></div>

    {{-- CDN Library html5-qrcode diletakkan LEBIH DULU agar class terbaca oleh Browser --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const VERIFY_URL = "{{ route('admin.registrations.scan.verify') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            // Inisialisasi Objek Scanner menggunakan Library (Aman karena CDN sudah di-load di atas)
            let html5QrCode = new Html5Qrcode("reader");
            let html5QrCodeFile = new Html5Qrcode("fileScannerTmp");

            const cameraSelect = document.getElementById('cameraSelect');
            const startCamBtn = document.getElementById('startCamBtn');
            const stopCamBtn = document.getElementById('stopCamBtn');
            const scannerOverlay = document.getElementById('scannerOverlay');
            const laserLine = document.getElementById('laserLine');
            const resultContainer = document.getElementById('resultContainer');
            const logTableBody = document.getElementById('logTableBody');

            let currentCameraId = null;
            let isScanning = false;

            // Load Daftar Kamera yang Tersedia
            function loadCameras() {
                Html5Qrcode.getCameras().then(cameras => {
                    cameraSelect.innerHTML = '';
                    if (cameras && cameras.length > 0) {
                        cameras.forEach((camera, index) => {
                            const option = document.createElement('option');
                            option.value = camera.id;
                            option.text = camera.label || `Kamera ${index + 1}`;
                            if (index === 0) currentCameraId = camera.id;
                            cameraSelect.appendChild(option);
                        });
                        startCamBtn.disabled = false;
                    } else {
                        cameraSelect.innerHTML = '<option value="">Kamera tidak ditemukan</option>';
                        startCamBtn.disabled = true;
                    }
                }).catch(err => {
                    console.error("Gagal mendapatkan akses list kamera: ", err);
                    cameraSelect.innerHTML = '<option value="">Gagal mendeteksi perangkat</option>';
                    startCamBtn.disabled = true;
                });
            }

            cameraSelect.addEventListener('change', function() {
                currentCameraId = this.value;
                if (isScanning) {
                    stopCamera().then(() => startCamera(currentCameraId));
                }
            });

            function startCamera(cameraId) {
                if (!cameraId) return;
                scannerOverlay.classList.add('d-none');
                laserLine.classList.remove('d-none');
                stopCamBtn.classList.remove('d-none');
                startCamBtn.parentNode.classList.add('d-none');

                html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: (width, height) => { return { width: width * 0.7, height: width * 0.7 }; } },
                    (decodedText) => {
                        if (decodedText) {
                            // Beri feedback audio singkat atau getaran jika didukung browser
                            if (navigator.vibrate) navigator.vibrate(100);
                            callVerifyAPI(decodedText.trim());
                        }
                    },
                    () => {}
                ).then(() => {
                    isScanning = true;
                }).catch(err => {
                    console.error(err);
                    scannerOverlay.classList.remove('d-none');
                    laserLine.classList.add('d-none');
                    stopCamBtn.classList.add('d-none');
                    startCamBtn.parentNode.classList.remove('d-none');
                    isScanning = false;
                });
            }

            function stopCamera() {
                return new Promise((resolve) => {
                    if (!isScanning) return resolve();
                    html5QrCode.stop().then(() => {
                        scannerOverlay.classList.remove('d-none');
                        laserLine.classList.add('d-none');
                        stopCamBtn.classList.add('d-none');
                        startCamBtn.parentNode.classList.remove('d-none');
                        isScanning = false;
                        resolve();
                    }).catch(err => {
                        console.error("Gagal menghentikan kamera: ", err);
                        resolve();
                    });
                });
            }

            startCamBtn.addEventListener('click', () => startCamera(currentCameraId));
            stopCamBtn.addEventListener('click', () => stopCamera());

            // Fungsi AJAX Utama untuk Verifikasi via Endpoint Laravel
            function callVerifyAPI(code) {
                if (!code) return;

                // Tampilkan indikator loading pada panel hasil verifikasi
                resultContainer.innerHTML = `
                    <div class="card h-100 min-vh-25 d-flex align-items-center justify-content-center p-5 text-center bg-white border">
                        <div class="text-muted">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <h5>Memproses Data...</h5>
                            <p class="mb-0 small">Menghubungi server untuk mencocokkan kode tiket <strong>${code}</strong></p>
                        </div>
                    </div>`;

                fetch(VERIFY_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ code: code })
                })
                .then(response => {
                    if (!response.ok && response.status !== 404 && response.status !== 422) {
                        throw new Error('Gangguan koneksi atau sistem server.');
                    }
                    return response.json();
                })
                .then(res => {
                    if (res.status === 'success') {
                        displayResult(res.data, 'success', res.message);
                        prependLogTable(res.data);
                    } else if (res.status === 'warning') {
                        displayResult(res.data, 'warning', res.message);
                        prependLogTable(res.data);
                    } else if (res.data) {
                        displayResult(res.data, 'error', res.message);
                    } else {
                        displayResult(null, 'error', res.message || 'Terjadi kesalahan sistem.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    displayResult(null, 'error', 'Gagal memproses verifikasi: ' + err.message);
                });
            }

            // Memasukkan Log yang Berhasil atau Berstatus Warning Baru ke Tabel Real-time
            function prependLogTable(data) {
                if (!data) return;
                
                const emptyRow = document.getElementById('emptyRow');
                if (emptyRow) emptyRow.remove();

                const now = new Date();
                const timeString = now.toTimeString().split(' ')[0];

                const newRow = document.createElement('tr');
                newRow.style.animation = "fadeSlideIn 0.3s ease-out both";
                newRow.innerHTML = `
                    <td><small class="text-muted fw-semibold">${data.verified_at || timeString}</small></td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark">${data.name}</span>
                            <small class="text-muted text-truncate" style="max-width: 150px;">${data.department}</small>
                        </div>
                    </td>
                    <td><span class="badge bg-label-primary font-monospace">${data.ticket_code}</span></td>
                    <td><span class="text-truncate d-inline-block" style="max-width: 180px;">${data.event}</span></td>
                    <td><small class="badge bg-label-secondary">${data.verified_by || 'Admin'}</small></td>
                `;
                logTableBody.insertBefore(newRow, logTableBody.firstChild);
            }

            // Fungsi untuk Merender Card Hasil Validasi Tiket di Sisi Kanan Halaman
            function displayResult(data, type, message = '') {
                let cardClass = 'bg-light border-secondary';
                let iconClass = 'bx-scan text-secondary';
                let alertClass = 'alert-secondary';
                let heading = 'Hasil Pemindaian';

                if (type === 'success') {
                    cardClass = 'bg-white border-success border-2 shadow-sm';
                    iconClass = 'bx-check-circle text-success';
                    alertClass = 'alert-success';
                    heading = 'Verifikasi Berhasil';
                } else if (type === 'warning') {
                    cardClass = 'bg-white border-warning border-2 shadow-sm';
                    iconClass = 'bx-error text-warning';
                    alertClass = 'alert-warning';
                    heading = 'Peringatan Verifikasi';
                } else if (type === 'error') {
                    cardClass = 'bg-white border-danger border-2 shadow-sm';
                    iconClass = 'bx-x-circle text-danger';
                    alertClass = 'alert-danger';
                    heading = 'Verifikasi Gagal';
                }

                let infoHtml = `
                    <div class="alert ${alertClass} d-flex align-items-center mb-3 py-2 small" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <div>${message}</div>
                    </div>`;

                let detailsHtml = data ? `
                    <div class="table-responsive small mt-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted ps-0 py-1" style="width: 110px;">Nama Peserta</td><td class="fw-bold text-dark py-1">: ${data.name}</td></tr>
                            <tr><td class="text-muted ps-0 py-1">Kode Tiket</td><td class="font-monospace fw-bold text-primary py-1">: ${data.ticket_code}</td></tr>
                            <tr><td class="text-muted ps-0 py-1">Nama Event</td><td class="text-dark py-1">: ${data.event}</td></tr>
                            <tr><td class="text-muted ps-0 py-1">Jurusan / Tim</td><td class="text-muted py-1">: ${data.department} <span class="mx-1">|</span> Tim: ${data.team_name}</td></tr>
                            <tr><td class="text-muted ps-0 py-1">Status Daftar</td><td class="py-1">: <span class="badge ${data.status === 'diterima' ? 'bg-label-success' : (data.status === 'pending' ? 'bg-label-warning' : 'bg-label-danger')} btn-sm rounded-pill px-2 py-0">${data.status.toUpperCase()}</span></td></tr>
                        </table>
                    </div>` : `
                    <div class="text-center py-3">
                        <p class="text-muted mb-0 small">Tidak ada detail informasi yang dapat dimuat untuk kode ini.</p>
                    </div>`;

                resultContainer.innerHTML = `
                    <div class="card ${cardClass} h-100 transition-all duration-300">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3">
                                <div class="avatar avatar-md flex-shrink-0">
                                    <span class="avatar-initial rounded-3 bg-label-light"><i class="bx ${iconClass} fs-2"></i></span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-dark">${heading}</h5>
                                    <small class="text-muted font-monospace">${data ? data.ticket_code : 'KODE_ERROR'}</small>
                                </div>
                            </div>
                            ${infoHtml}
                            ${detailsHtml}
                        </div>
                    </div>`;
            }

            /* ───── Perbaikan Panel Input Manual ───── */
            document.getElementById('manualVerifyBtn').addEventListener('click', function() {
                const codeInput = document.getElementById('manualCodeInput');
                const code = codeInput.value.trim();
                if (!code) return;

                const btn = this;
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>...';

                callVerifyAPI(code);

                // Kembalikan keadaan tombol setelah request selesai diproses
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    codeInput.value = '';
                }, 1200);
            });

            /* ───── Perbaikan Panel Unggah File QR ───── */
            document.getElementById('qrFileInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                e.target.value = ''; // Reset input file cache
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    displayResult(null, 'error', 'File yang dipilih harus berupa format gambar (PNG, JPG, JPEG).');
                    return;
                }

                resultContainer.innerHTML = `
                    <div class="card h-100 min-vh-25 d-flex align-items-center justify-content-center p-5 text-center bg-white border">
                        <div class="text-muted">
                            <div class="spinner-border text-info mb-3" role="status"></div>
                            <h5>Membaca Gambar...</h5>
                            <p class="mb-0 small">Mengekstrak baris kode QR dari file gambar terpilih.</p>
                        </div>
                    </div>`;

                if (typeof html5QrCodeFile.scanFile !== 'function') {
                    displayResult(null, 'error', 'Fitur decode file tidak didukung oleh browser Anda.');
                    return;
                }

                // Proses decode gambar
                html5QrCodeFile.scanFile(file, true)
                    .then(decodedText => {
                        if (decodedText) {
                            callVerifyAPI(decodedText.trim());
                        } else {
                            throw new Error('Hasil scan mengembalikan data kosong.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        displayResult(null, 'error', 'Gagal membaca QR Code dari file tersebut. Pastikan gambar tajam, terang, dan QR terlihat penuh.');
                    });
            });

            // Jalankan pencarian device kamera di awal load
            loadCameras();
        });
    </script>

    <style>
        @keyframes scanAnim {
            0%   { top: 4px; }
            50%  { top: calc(100% - 7px); }
            100% { top: 4px; }\
        }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        #reader { border-radius: 12px; overflow: hidden; }
        #reader video { border-radius:0 !important; width:100% !important; }
        #reader img, #reader canvas + div { display:none !important; }
        #reader__scan_region { border-radius: 12px; overflow: hidden; }
        #reader__dashboard { display: none !important; }
    </style>
</body>
</html>