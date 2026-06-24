<!DOCTYPE html>
<html>

<head>
    <title>Kode OTP Registrasi</title>
</head>

<body>
    <h2>Halo!</h2>
    @if($type === 'reset')
        <p>Kami menerima permintaan untuk melakukan reset password di <strong>Event Kampus</strong>.</p>
        <p>Gunakan kode OTP berikut untuk melanjutkan proses reset password kamu:</p>
    @else
        <p>Terima kasih telah mendaftar di <strong>Event Kampus</strong>.</p>
        <p>Gunakan kode OTP berikut untuk memverifikasi akun kamu:</p>
    @endif
    <h1 style="color: #696cff;">{{ $otp }}</h1>
    <p>Kode ini berlaku selama 10 menit. Jangan bagikan kode ini kepada siapapun.</p>
    <p>Terima kasih!</p>
</body>

</html>