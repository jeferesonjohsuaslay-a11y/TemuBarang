<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $kode }} | TemuBarang</title>
    @include('layouts._head')
</head>
<body class="halaman-galat">
    <main class="galat">
        <p class="galat__kode">{{ $kode }}</p>
        <h1>{{ $judul }}</h1>
        <p>{{ $isi }}</p>
        <a href="{{ url('/') }}" class="tombol tombol--utama">Kembali ke beranda</a>
    </main>
</body>
</html>
