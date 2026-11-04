@extends('layouts.app')
@section('judul', 'Dashboard admin')

@section('isi')
    <div class="judul-halaman">
        <div>
            <h1>Dashboard admin</h1>
            <p>Pantau, periksa, dan kelola seluruh laporan yang masuk.</p>
        </div>
    </div>

    <section class="statistik" aria-label="Ringkasan">
        <div class="stat"><span class="stat__angka">{{ $jumlahHilang + $jumlahDitemukan + $jumlahSelesai }}</span><span class="stat__label">Total laporan</span></div>
        <div class="stat stat--hilang"><span class="stat__angka">{{ $jumlahHilang }}</span><span class="stat__label">Masih hilang</span></div>
        <div class="stat stat--ditemukan"><span class="stat__angka">{{ $jumlahDitemukan }}</span><span class="stat__label">Ditemukan</span></div>
        <div class="stat stat--selesai"><span class="stat__angka">{{ $jumlahSelesai }}</span><span class="stat__label">Selesai</span></div>
        <div class="stat"><span class="stat__angka">{{ $jumlahPengguna }}</span><span class="stat__label">Mahasiswa terdaftar</span></div>
    </section>

    @include('laporan._filter', ['aksi' => route('admin.dashboard')])

    <p class="hasil-info" aria-live="polite">Menampilkan <strong>{{ $laporan->total() }}</strong> laporan</p>

    @if ($laporan->isEmpty())
        <div class="kosong">
            <x-icon name="inbox" :size="40" />
            <h3>Tidak ada laporan</h3>
            <p>Belum ada laporan yang cocok dengan filter ini.</p>
        </div>
    @else
        <div class="tabel-bungkus">
            <table class="tabel">
                <thead>
                    <tr><th>Barang</th><th>Pelapor</th><th>Jenis</th><th>Tanggal</th><th>Status</th><th class="kanan">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $item)
                        <tr>
                            <td data-label="Barang">
                                <a href="{{ route('laporan.show', $item) }}" class="sel-barang">
                                    <span class="mini-foto">
                                        @if ($item->urlFoto()) <img src="{{ $item->urlFoto() }}" alt=""> @else {{ $item->kategori->ikon ?? '📦' }} @endif
                                    </span>
                                    <span><strong>{{ $item->nama_barang }}</strong><small>{{ $item->lokasi }}</small></span>
                                </a>
                            </td>
                            <td data-label="Pelapor">{{ $item->pelapor->name ?? '-' }}</td>
                            <td data-label="Jenis"><span class="label-jenis label-jenis--{{ $item->jenis }} label-jenis--inline">{{ $item->getLabelJenis() }}</span></td>
                            <td data-label="Tanggal">{{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</td>
                            <td data-label="Status">
                                <form method="POST" action="{{ route('laporan.status', $item) }}" class="form-status">
                                    @csrf @method('PATCH')
                                    <label class="sr-only" for="status-{{ $item->id }}">Ubah status {{ $item->nama_barang }}</label>
                                    <select id="status-{{ $item->id }}" name="status" class="status-{{ $item->status }}" data-kirim-otomatis>
                                        @foreach ($item->statusYangBolehDipilih() as $s)
                                            <option value="{{ $s }}" @selected($item->status === $s)>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                    <noscript><button class="tombol tombol--ghost tombol--kecil">Simpan</button></noscript>
                                </form>
                            </td>
                            <td class="kanan">
                                <div class="aksi-baris">
                                    <a href="{{ route('laporan.show', $item) }}" class="tombol tombol--ghost tombol--kecil">Lihat</a>
                                    <a href="{{ route('laporan.edit', $item) }}" class="tombol tombol--ghost tombol--kecil">Ubah</a>
                                    <form method="POST" action="{{ route('laporan.destroy', $item) }}"
                                          data-konfirmasi="Hapus laporan &quot;{{ $item->nama_barang }}&quot;? Gunakan ini untuk laporan yang tidak sesuai atau tidak pantas." data-tombol="Ya, hapus">
                                        @csrf @method('DELETE')
                                        <button class="tombol tombol--bahaya-garis tombol--kecil">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $laporan->links('vendor.pagination.temubarang') }}
    @endif
@endsection
