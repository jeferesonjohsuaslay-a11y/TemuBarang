@extends('layouts.app')
@section('judul', 'Semua laporan')

@section('isi')
    <div class="judul-halaman">
        <div>
            <h1>Semua laporan</h1>
            <p>Cari barangmu di antara laporan barang hilang dan barang temuan.</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="tombol tombol--utama"><x-icon name="plus" :size="18" /> Buat laporan</a>
    </div>

    @include('laporan._filter', ['aksi' => route('laporan.index')])

    <p class="hasil-info" aria-live="polite">
        Menampilkan <strong>{{ $laporan->total() }}</strong> laporan
        @if (collect($filter)->filter()->isNotEmpty()) sesuai filter @endif
    </p>

    @if ($laporan->isEmpty())
        <div class="kosong">
            <x-icon name="search" :size="40" />
            <h3>Tidak ada laporan yang cocok</h3>
            <p>Coba kata kunci lain atau kurangi filter. Kalau barangnya memang belum dilaporkan, kamu bisa membuatnya sendiri.</p>
            <a href="{{ route('laporan.create') }}" class="tombol tombol--utama">Buat laporan</a>
        </div>
    @else
        <div class="grid-kartu">
            @foreach ($laporan as $item)
                <x-kartu-laporan :laporan="$item" />
            @endforeach
        </div>
        {{ $laporan->links('vendor.pagination.temubarang') }}
    @endif
@endsection
