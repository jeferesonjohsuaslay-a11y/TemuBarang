@extends('layouts.auth')
@section('judul', 'Daftar')

@section('isi')
    <h2>Buat akun mahasiswa</h2>
    <p class="auth__sub">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>

    <form method="POST" action="{{ route('register.proses') }}" class="form" novalidate>
        @csrf

        <div class="field">
            <label for="name">Nama lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required
                   class="{{ $errors->has('name') ? 'input--galat' : '' }}">
            @error('name') <p class="field__galat">{{ $message }}</p> @enderror
        </div>

        <div class="baris-2">
            <div class="field">
                <label for="nim">NIM</label>
                <input type="text" id="nim" name="nim" value="{{ old('nim') }}" inputmode="numeric" required
                       class="{{ $errors->has('nim') ? 'input--galat' : '' }}">
                @error('nim') <p class="field__galat">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="no_hp">Nomor HP <span class="opsional">(opsional)</span></label>
                <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" inputmode="tel" placeholder="08xxxxxxxxxx" autocomplete="tel"
                       class="{{ $errors->has('no_hp') ? 'input--galat' : '' }}">
                @error('no_hp') <p class="field__galat">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required
                   class="{{ $errors->has('email') ? 'input--galat' : '' }}">
            @error('email') <p class="field__galat">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <div class="input-sandi">
                <input type="password" id="password" name="password" autocomplete="new-password" required
                       class="{{ $errors->has('password') ? 'input--galat' : '' }}">
                <button type="button" class="input-sandi__tombol" data-tampil-sandi="password">Tampilkan</button>
            </div>
            <p class="field__bantu">Minimal 8 karakter.</p>
            @error('password') <p class="field__galat">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Ulangi kata sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
        </div>

        <button type="submit" class="tombol tombol--utama tombol--penuh">Buat akun</button>
    </form>
@endsection
