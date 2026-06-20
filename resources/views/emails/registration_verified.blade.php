<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Diverifikasi</title>
</head>
<body>
    <h2>Selamat!</h2>
    <p>Halo <strong>{{ $registration->user->name }}</strong>,</p>
    <p>Pendaftaran kamu untuk event <strong>{{ $registration->event->title }}</strong> telah berhasil diverifikasi oleh admin.</p>
    <p>Silakan cek detail event di dashboard peserta kamu.</p>
    <p>Terima kasih telah berpartisipasi!</p>
</body>
</html>
