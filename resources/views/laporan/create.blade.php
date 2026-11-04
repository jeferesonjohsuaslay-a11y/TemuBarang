@extends('layouts.app')
@section('judul', 'Buat laporan')

@section('isi')
    <a href="{{ url()->previous() === url()->current() ? route('home') : url()->previous() }}" class="kembali"><x-icon name="arrow-left" :size="18" /> Kembali</a>
    <div class="judul-halaman">
        <div>
            <h1>Buat laporan</h1>
            <p>Semakin jelas informasinya, semakin cepat barang menemukan pemiliknya.</p>
        </div>
    </div>

    <div class="kotak-form">
        @include('laporan._form', [
            'aksi' => route('laporan.store'),
            'metode' => 'POST',
            'batal' => route('home'),
        ])
    </div>
@endsection
