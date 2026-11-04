@extends('layouts.app')
@section('judul', 'Ubah laporan')

@section('isi')
    <a href="{{ route('laporan.show', $laporan) }}" class="kembali"><x-icon name="arrow-left" :size="18" /> Kembali ke detail</a>
    <div class="judul-halaman">
        <div>
            <h1>Ubah laporan</h1>
            <p><span class="label-jenis label-jenis--{{ $laporan->jenis }} label-jenis--inline">{{ $laporan->getLabelJenis() }}</span> Jenis laporan tidak bisa diubah.</p>
        </div>
    </div>

    <div class="kotak-form">
        @include('laporan._form', [
            'aksi' => route('laporan.update', $laporan),
            'metode' => 'PUT',
            'batal' => route('laporan.show', $laporan),
        ])
    </div>
@endsection
