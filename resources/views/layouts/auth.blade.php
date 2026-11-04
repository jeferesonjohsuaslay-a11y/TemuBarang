<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul') | TemuBarang</title>
    @include('layouts._head')
</head>
<body class="halaman-auth">
    <div class="auth">
        <aside class="auth__panel">
            <a href="{{ route('login') }}" class="merek merek--terang">
                <span class="merek__tag" aria-hidden="true"></span>
                <span class="merek__nama">TemuBarang</span>
            </a>
            <div class="auth__pesan">
                <h1>Barang tertinggal di kampus? Biar kami bantu kembali.</h1>
                <ul class="auth__poin">
                    <li><x-icon name="search" :size="20" /> Cari barang berdasarkan nama, kategori, lokasi, dan tanggal.</li>
                    <li><x-icon name="package" :size="20" /> Laporkan barang hilang atau barang yang kamu temukan.</li>
                    <li><x-icon name="message" :size="20" /> Hubungi pemilik atau penemu langsung lewat WhatsApp.</li>
                </ul>
            </div>
            <p class="auth__kaki">Khusus mahasiswa. Daftar dulu untuk melihat laporan.</p>
        </aside>

        <section class="auth__form">
            <div class="auth__kotak">
                @include('layouts._pesan')
                @yield('isi')
            </div>
        </section>
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
