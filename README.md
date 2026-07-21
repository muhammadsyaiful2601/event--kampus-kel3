# Product Requirements Document: Sistem Manajemen Event Kampus

## 1. Latar Belakang & Masalah

Sistem ini dibangun untuk mengatasi masalah pengelolaan event kampus yang masih dilakukan secara manual atau semi-digital. Beberapa masalah utama yang ingin diselesaikan:

- Pendaftaran peserta event masih menggunakan formulir fisik atau spreadsheet yang tidak terstruktur.
- Verifikasi kehadiran peserta di lokasi event belum terotomatisasi.
- Manajemen event oleh admin belum terpusat dan terdokumentasi dengan baik.
- Tidak adanya sistem notifikasi dan integrasi email untuk proses verifikasi.
- Penyelipatan data dan ketidaksesuaian status kehadiran antar event.

## 2. Tujuan & Ruang Lingkup

### 2.1 Tujuan
- Menyediakan platform digital terpadu untuk pengelolaan event kampus.
- Meningkatkan efisiensi proses pendaftaran, verifikasi, dan pelaporan kehadiran.
- Meminimalkan fraud dan human error melalui verifikasi QR code.
- Memberikan transparansi status pendaftaran kepada peserta.

### 2.2 Ruang Lingkup
- Manajemen event (CRUD) oleh admin.
- Pendaftaran event oleh peserta.
- Verifikasi pendaftaran oleh admin.
- Scan QR untuk absensi di lokasi event.
- Pelaporan dan log aktivitas admin.

### 2.3 Di Luar Ruang Lingkup
- Pembayaran online atau integrasi payment gateway.
- Integrasi SSO kampus (menggunakan email/OTP sebagai substitusi).
- Aplikasi mobile native (hanya responsive web app).

## 3. Kebutuhan Fungsional

### 3.1 Publik (Tanpa Login)
- Melihat daftar event yang sedang berlangsung dan akan datang.
- Melihat detail event (deskripsi, tanggal, lokasi, kuota, tipe event).
- Registrasi akun peserta dengan verifikasi OTP.
- Lupa password dengan reset via OTP.

### 3.2 Peserta (Login)
- Dashboard peserta menampilkan event aktif, daftar pendaftaran saya, dan statistik.
- Pendaftaran event (solo/duo/tim) dengan form dinamis sesuai tipe event.
- Pembatalan pendaftaran (hanya status pending).
- Melihat detail tiket dan QR code.
- Mengunduh dokumen peserta (HTML dengan embedded QR).
- Mengelola profil (opsional, untuk keperluan data diri).

### 3.3 Admin (Login)
- Dashboard statistik (event, pendaftaran, admin).
- Manajemen event (tambah, edit, hapus, kelola status dan tipe event).
- Manajemen pendaftaran (verifikasi/tolak, edit status, hapus).
- Verifikasi kehadiran via scan QR code.
- Manajemen sesi absen (buka/tutup sesi, reset status kehadiran).
- Log aktivitas admin (read-only, melacak aksi sensitif).

## 4. Kebutuhan Non-Fungsional

### 4.1 Keamanan
- Autentikasi berbasis session dengan email dan password.
- Verifikasi OTP untuk registrasi dan reset password.
- Otorisasi berbasis role (admin vs peserta).
- Perlindungan CSRF pada semua form.
- Log aktivitas admin yang tidak dapat diubah atau dihapus.

### 4.2 Kinerja
- Responsive design untuk desktop dan mobile.
- Pagination pada daftar pendaftaran dan log (10-25 item per halaman).
- Optimasi query dengan eager loading (`with`) untuk relasi user dan event.
- Cache session dan asset melalui Vite.

### 4.3 Kegunaan (Usability)
- Antarmuka bahasa Indonesia dengan dukungan internasionalisasi.
- Notifikasi flash message untuk feedback aksi pengguna.
- Pencarian dan filter pada halaman pendaftaran dan log admin.

### 4.4 Keandalan
- Status transisi pendaftaran memiliki state machine yang ketat.
- Peserta yang scan setelah sesi absen ditutup ditandai terlambat.
- Peserta yang status pendaftaran ditolak tidak diperbolehkan masuk meskipun punya QR code.

## 5. Arsitektur Teknis

### 5.1 Teknologi
- **Backend**: Laravel 13 (PHP 8.3+)
- **Frontend**: Blade templating, Vite (asset bundling)
- **Database**: MySQL (disarankan) / SQLite (untuk pengembangan)
- **Library QR**: `simplesoftwareio/simple-qrcode` untuk generate QR code.

### 5.2 Pola Arsitektur
- **MVC (Model-View-Controller)**: sesuai standar Laravel.
- **Repository Pattern**: tidak dipaksa, logika bisnis berada di Controller dan Model.
- **Middleware**: autentikasi (`auth`), otorisasi manual via `Auth::user()->role`.

### 5.3 Struktur Direktori Utama
```
app/
  Http/Controllers/   -> Logika bisnis
  Models/             -> Eloquent models
  Helpers/            -> Utility (AdminActivityLogger)
  Mail/               -> Mailable classes
  Middleware/         -> Middleware kustom
database/
  migrations/         -> Skema database
resources/
  views/              -> Blade templates
routes/
  web.php             -> Routing aplikasi
config/
  session.php         -> Konfigurasi session
lang/
  id/, en/            -> Terjemahan
```

## 6. Peran Pengguna

| Peran | Deskripsi | Hak Akses |
|-------|-----------|-----------|
| **Peserta** | Pengguna yang mendaftar event | - Melihat event<br>- Mendaftar/membatalkan pendaftaran<br>- Melihat tiket dan QR code<br>- Mengunduh dokumen peserta |
| **Admin** | Pengelola event dan pendaftaran | - Manajemen event (CRUD)<br>- Verifikasi pendaftaran<br>- Scan QR absensi<br>- Manajemen sesi absen<br>- Melihat log aktivitas admin |
| **Superadmin (Opsional)** | Sebagai admin dengan tambahan manajemen akun admin | - CRUD akun admin<br>- Mengelola semua data |

## 7. Rincian Fitur

### 7.1 Autentikasi & Otorisasi
- **Registrasi** dengan email, nama, password, dan role default 'peserta'.
- **Login** dengan email dan password.
- **Logout** dengan session destroy.
- **OTP Verification**: 6 digit kode dikirim via email/session untuk verifikasi registrasi dan reset password.
- **Lupa Password**: Alur reset password menggunakan OTP. Password baru disimpan setelah verifikasi OTP.

### 7.2 Event Management
- **Kriteria Event**: judul, deskripsi, tanggal, lokasi, kuota (opsional), gambar, status (mendatang/berlangsung), tipe (solo/duo/tim), dan toggle pendaftaran.
- **Halaman Landing**: menampilkan event yang sedang berlangsung dan akan datang untuk pengguna publik.
- **Halaman Dashboard Peserta**: menampilkan event yang sedang berlangsung, akan datang, dan riwayat pendaftaran saya.
- **CRUD Event** untuk admin.

### 7.3 Pendaftaran & Tiket
- **Form Pendaftaran**: peserta mengisi data diri (nama, jurusan, angkatan, usia, foto). Untuk event tim, ada input nama tim dan cadangan.
- **Duplicate Check**: sistem mencegah pendaftaran ganda.
- **Kuota Check**: pendaftaran ditolak jika kuota penuh.
- **Ticket Code**: kode unik 10 karakter acak untuk setiap pendaftaran.
- **Status Pendaftaran**:
  - `pending` - menunggu verifikasi admin.
  - `diterima` - disetujui, QR code aktif.
  - `ditolak` - tidak diizinkan.

### 7.4 Verifikasi & Absensi
- **Verifikasi Manual**: admin dapat menyetujui/menolak pendaftaran.
- **Scan QR**: admin melakukan scan kode QR tiket peserta.
- **Konfirmasi Kehadiran**:
  - Jika sesi absen **dibuka**: peserta diverifikasi.
  - Jika sesi absen **ditutup**: peserta ditandai **TERLAMBAT** (`is_late = true`) namun tetap tercatat hadir.
  - Jika status pendaftaran **ditolak**: scan ditolak.
  - Jika **sudah pernah diverifikasi**: sistem memberikan peringatan.

### 7.5 Manajemen Sesi Absen
- Admin dapat membuka atau menutup sesi absen untuk setiap event.
- Membuka sesi akan mereset semua status kehadiran (verified_at, attended_at, is_late) pada event tersebut.

### 7.6 Log Aktivitas Admin
- Semua aksi sensitif admin dicatat secara otomatis:
  - `event.created`, `event.updated`, `event.deleted`
  - `registration.verified`, `registration.rejected`, `registration.scan_verified`, `registration.deleted`
  - `attendance.opened`, `attendance.closed`
- Log bersifat **immutable** (hanya dibaca, tidak bisa diedit/dihapus).

## 8. Alur Pengguna (User Flow)

### 8.1 Register & OTP
1. Pengunjung buka halaman landing.
2. Klik **Daftar**, isi form registrasi (nama, email, password).
3. Sistem mengirim OTP dan menampilkan halaman verifikasi OTP.
4. Pengguna memasukkan 6-digit kode.
5. Akun aktif, redirect ke login.

### 8.2 Login & Peserta Dashboard
1. Pengguna login dengan email dan password.
2. Sistem redirect sesuai role:
   - Admin ke `/admin/dashboard`.
   - Peserta ke `/peserta/dashboard`.

### 8.3 Pendaftaran Event
1. Peserta melihat daftar event yang akan datang.
2. Klik **Daftar** pada event yang diinginkan.
3. Isi form pendaftaran (data diri, foto, dan info tim jika event bertipe tim).
4. Sistem membuat `registration` dengan status `pending` dan kode tiket unik.

### 8.4 Verifikasi Admin
1. Admin melihat daftar pendaftaran di `/admin/registrations`.
2. Admin menyetujui atau menolak pendaftaran.
3. Jika disetujui, sistem mengirim email notifikasi dan mengaktifkan QR code.

### 8.5 Scan QR & Absensi
1. Admin membuka sesi absen untuk event.
2. Admin melakukan scan QR code peserta.
3. Sistem memverifikasi:
   - QR valid? 
   - Status pendaftaran?
   - Sudah diverifikasi?
   - Sesi absen dibuka?
4. Sistem mencatat status kehadiran (dan terlambat jika sesi sudah ditutup).

## 9. Database Schema

### 9.1 Tabel `events`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| title | varchar | Judul event |
| description | text | Deskripsi event |
| date | date | Tanggal event |
| location | varchar | Lokasi event |
| image | varchar (nullable) | Path gambar event |
| quota | integer (nullable) | Kuota peserta (unlimited jika null) |
| status | enum | `berlangsung`, `mendatang` |
| type | enum | `solo`, `duo`, `tim` |
| is_registration_open | boolean | Status pendaftaran |
| is_attendance_open | boolean | Status sesi absen |
| created_at | timestamp | |
| updated_at | timestamp | |

### 9.2 Tabel `registrations`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| user_id | bigint (FK) | Relasi ke users |
| event_id | bigint (FK) | Relasi ke events |
| participant_name | varchar | Nama peserta |
| team_name | varchar (nullable) | Nama tim (untuk event tim) |
| substitutes | text (nullable) | Daftar cadangan tim |
| department | varchar | Jurusan |
| year | varchar | Angkatan |
| age | integer | Usia |
| participant_photo | varchar | Path foto peserta |
| ticket_code | varchar | Kode tiket unik |
| status | enum | `pending`, `diterima`, `ditolak` |
| verified_at | datetime (nullable) | Waktu verifikasi kehadiran |
| verified_by | varchar (nullable) | Admin yang memverifikasi |
| attended_at | datetime (nullable) | Waktu kehadiran tercatat |
| is_late | boolean | Status keterlambatan |
| created_at | timestamp | |
| updated_at | timestamp | |

### 9.3 Tabel `admin_logs`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| admin_id | bigint (FK) | Relasi ke users (admin) |
| action | varchar | Kategori aksi |
| description | text | Deskripsi aksi |
| subject_type | varchar | Tipe entitas yang diubah |
| subject_id | bigint (nullable) | ID entitas |
| ip_address | varchar (nullable) | Alamat IP admin |
| user_agent | text (nullable) | User agent browser |
| created_at | timestamp | |
| updated_at | timestamp | |

### 9.4 Tabel `otp_verifications` (untuk registrasi & reset password)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| email | varchar | Email tujuan OTP |
| otp | varchar | Kode OTP 6 digit |
| type | enum | `registration`, `password_reset` |
| expires_at | datetime | Waktu kadaluarsa OTP |
| created_at | timestamp | |
| updated_at | timestamp | |

## 10. Deployment & Setup

### 10.1 Prasyarat
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL atau SQLite
- Web server (Apache/Nginx) atau `php artisan serve`

### 10.2 Langkah-langkah Instalasi
```bash
# 1. Clone repository
git clone https://github.com/muhammadsyaiful2601/event--kampus-kel3.git
cd event--kampus-kel3

# 2. Install dependencies
composer install
npm install

# 3. Salin file environment
cp .env.example .env

# 4. Set kunci aplikasi
php artisan key:generate

# 5. Konfigurasi database di .env (DB_CONNECTION, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 6. Migrasi database
php artisan migrate

# 7. Build asset frontend
npm run build

# 8. Jalankan server pengembangan
php artisan serve
```

### 10.3 Catatan Penggayaan
- Pastikan mail driver dikonfigurasi di `.env` untuk mengirim OTP dan notifikasi.
- Folder `storage` harus writable oleh web server.
- Session driver default: `file` (sesuai `config/session.php`).

## 11. Batasan & Asumsi

- OTP dikirim melalui session atau email tergantung konfigurasi mail driver.
- Tanggal dan waktu mengikuti zona waktu server (default zona Asia/Jakarta).
- Admin dapat dibuatkan akun melalui seeder atau controller khusus (`AdminManagementController`).
- Proses verifikasi registrasi dan scan absensi memerlukan admin yang aktif.
