@extends('layouts.app')
@section('judul', 'Beranda')

@section('isi')
    {{-- HERO: pencarian adalah aksi utama --}}
    <section class="hero">
        <div class="hero__teks">
            <h1>Barangmu hilang di kampus? Cari di sini dulu.</h1>
            <p>Semua laporan barang hilang dan barang temuan dikumpulkan di satu tempat, jadi tidak tenggelam di grup chat.</p>

            <form action="{{ route('laporan.index') }}" method="GET" class="hero__cari" role="search">
                <label for="q" class="sr-only">Cari nama barang</label>
                <x-icon name="search" :size="22" />
                <input type="search" id="q" name="q" placeholder="Contoh: dompet, kunci motor, KTM">
                <button type="submit" class="tombol tombol--utama">Cari barang</button>
            </form>

            <ul class="chip-daftar" aria-label="Cari berdasarkan kategori">
                @foreach ($kategori as $k)
                    <li><a class="chip" href="{{ route('laporan.index', ['kategori' => $k->id]) }}"><span aria-hidden="true">{{ $k->ikon }}</span> {{ $k->nama }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- Tag barang: ringkasan jumlah laporan --}}
        <aside class="hero__tag" aria-label="Ringkasan laporan">
            <div class="tag-barang">
                <span class="tag-barang__lubang" aria-hidden="true"></span>
                <p class="tag-barang__judul">Menunggu pemiliknya</p>
                <p class="tag-barang__angka">{{ $jumlahDitemukan }}</p>
                <p class="tag-barang__ket">barang temuan belum diambil</p>
                <dl class="tag-barang__rinci">
                    <div><dt><span class="titik titik--hilang"></span> Dilaporkan hilang</dt><dd>{{ $jumlahHilang }}</dd></div>
                    <div><dt><span class="titik titik--selesai"></span> Sudah kembali</dt><dd>{{ $jumlahSelesai }}</dd></div>
                </dl>
            </div>
        </aside>
    </section>

    {{-- Dua aksi utama --}}
    <section class="aksi-ganda" aria-label="Buat laporan">
        <a href="{{ route('laporan.create', ['jenis' => 'hilang']) }}" class="aksi aksi--hilang">
            <x-icon name="alert" :size="28" />
            <span>
                <strong>Saya kehilangan barang</strong>
                <small>Buat laporan agar orang lain bisa membantu mencari.</small>
            </span>
        </a>
        <a href="{{ route('laporan.create', ['jenis' => 'ditemukan']) }}" class="aksi aksi--ditemukan">
            <x-icon name="package" :size="28" />
            <span>
                <strong>Saya menemukan barang</strong>
                <small>Beri tahu pemiliknya di mana barang ini bisa diambil.</small>
            </span>
        </a>
    </section>

    {{-- Laporan terbaru --}}
    <section class="bagian">
        <div class="bagian__kepala">
            <h2>Laporan terbaru</h2>
            <a href="{{ route('laporan.index') }}" class="tautan">Lihat semua laporan</a>
        </div>

        @if ($terbaru->isEmpty())
            <div class="kosong">
                <x-icon name="inbox" :size="40" />
                <h3>Belum ada laporan</h3>
                <p>Jadilah yang pertama membuat laporan barang hilang atau temuan.</p>
                <a href="{{ route('laporan.create') }}" class="tombol tombol--utama">Buat laporan</a>
            </div>
        @else
            <div class="grid-kartu">
                @foreach ($terbaru as $laporan)
                    <x-kartu-laporan :laporan="$laporan" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- Cara kerja: urutan nyata, jadi memakai nomor --}}
    <section class="bagian">
        <h2>Cara kerja</h2>
        <ol class="langkah">
            <li>
                <span class="langkah__no">1</span>
                <h3>Buat laporan</h3>
                <p>Isi nama barang, lokasi, tanggal, dan foto jika ada.</p>
            </li>
            <li>
                <span class="langkah__no">2</span>
                <h3>Cari dan cocokkan</h3>
                <p>Gunakan pencarian dan filter untuk menemukan laporan yang cocok.</p>
            </li>
            <li>
                <span class="langkah__no">3</span>
                <h3>Hubungi lewat WhatsApp</h3>
                <p>Hubungi pelapor, lalu pastikan barang kembali ke pemilik.</p>
            </li>
            <li>
                <span class="langkah__no">4</span>
                <h3>Tandai selesai</h3>
                <p>Ubah status laporan menjadi Selesai agar tidak membingungkan orang lain.</p>
            </li>
        </ol>
    </section>
@endsection
