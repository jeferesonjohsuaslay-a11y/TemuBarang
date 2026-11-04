@extends('layouts.auth')
@section('judul', 'Masuk')

@section('isi')
    <h2>Masuk ke akunmu</h2>
    <p class="auth__sub">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>

    <form method="POST" action="{{ route('login.proses') }}" class="form" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required
                   class="{{ $errors->has('email') ? 'input--galat' : '' }}">
            @error('email') <p class="field__galat">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <div class="input-sandi">
                <input type="password" id="password" name="password" autocomplete="current-password" required
                       class="{{ $errors->has('password') ? 'input--galat' : '' }}">
                <button type="button" class="input-sandi__tombol" data-tampil-sandi="password">Tampilkan</button>
            </div>
            @error('password') <p class="field__galat">{{ $message }}</p> @enderror
        </div>

        <label class="centang">
            <input type="checkbox" name="ingat" value="1"> Ingat saya di perangkat ini
        </label>

        <button type="submit" class="tombol tombol--utama tombol--penuh">Masuk</button>
    </form>
@endsection
