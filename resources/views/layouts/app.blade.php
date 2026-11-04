<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') | TemuBarang</title>
    @include('layouts._head')
</head>
<body>
    <a href="#konten" class="lompat">Lewati ke konten</a>

    <header class="navbar">
        <div class="wadah navbar__isi">
            <a href="{{ route('home') }}" class="merek" aria-label="TemuBarang, ke beranda">
                <span class="merek__tag" aria-hidden="true"></span>
                <span class="merek__nama">TemuBarang</span>
            </a>

            <button type="button" class="navbar__tombol" id="tombol-menu" aria-expanded="false" aria-controls="menu-utama" aria-label="Buka menu">
                <x-icon name="menu" :size="24" />
            </button>

            <nav class="navbar__menu" id="menu-utama" aria-label="Menu utama">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'aktif' : '' }}">Beranda</a>
                <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.index', 'laporan.show') ? 'aktif' : '' }}">Semua laporan</a>
                <a href="{{ route('laporan.saya') }}" class="{{ request()->routeIs('laporan.saya', 'laporan.edit') ? 'aktif' : '' }}">Laporan saya</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'aktif' : '' }}">Dashboard admin</a>
                @endif

                <div class="navbar__aksi">
                    <a href="{{ route('laporan.create') }}" class="tombol tombol--utama tombol--kecil">
                        <x-icon name="plus" :size="18" /> Buat laporan
                    </a>
                    <div class="akun">
                        <span class="akun__avatar" aria-hidden="true">{{ auth()->user()->getInisial() }}</span>
                        <span class="akun__info">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ auth()->user()->getPeran() }}</small>
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="tombol tombol--ghost tombol--kecil" title="Keluar">
                            <x-icon name="logout" :size="18" /> Keluar
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    <main id="konten" class="wadah halaman">
        @include('layouts._pesan')
        @yield('isi')
    </main>

    <footer class="footer">
        <div class="wadah">
            <strong>TemuBarang</strong>
            <span>Pelaporan dan pencarian barang hilang di lingkungan kampus.</span>
        </div>
    </footer>

    @include('layouts._dialog')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
