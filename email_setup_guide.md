# Panduan Setup Email Otomatis di Laravel

Agar email (OTP dan Notifikasi) terkirim secara otomatis ke email asli, kamu harus mengubah konfigurasi di file `.env`. Saat ini sistem menggunakan link [log](file:///d:/Coding/Kelompok%203/tugas%20kelompok/app/Http/Controllers/AuthController.php#23-47) (hanya tercatat di internal).

## 1. Konfigurasi di file `.env`

Cari bagian `MAIL_...` di file `.env` dan ubah sesuai provider yang kamu gunakan.

### Opsi A: Menggunakan Gmail (Gratis & Mudah)
> [!IMPORTANT]
> Kamu harus menggunakan **App Password** dari Google, bukan password email biasa.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD=kode-app-password-kamu
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="emailkamu@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Opsi B: Menggunakan Brevo / Sendinblue (Professional - Gratis 300 email/hari)
1. Buat akun di [Brevo](https://www.brevo.com/).
2. Ambil SMTP Key dari dashboard Brevo.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=email-login-brevo@gmail.com
MAIL_PASSWORD=api-key-smtp-kamu
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@eventkampus.com"
MAIL_FROM_NAME="Event Kampus"
```

## 2. Cara Kerja (API vs SMTP)

- **Apakah harus menggunakan API?** Tidak wajib. Laravel memiliki sistem **SMTP** bawaan yang sangat mudah. Kamu hanya perlu mengisi data di atas.
- **Apakah otomatis?** Ya. Kode yang sudah saya buat menggunakan `Mail::to()->send()`. Begitu konfigurasi `.env` benar, Laravel akan langsung menghubungi server email tersebut.

## 3. Catatan Penting
Setiap kali kamu mengubah file `.env`, sangat disarankan untuk menjalankan perintah ini di terminal agar Laravel membaca perubahan terbaru:
```bash
php artisan config:clear
```
