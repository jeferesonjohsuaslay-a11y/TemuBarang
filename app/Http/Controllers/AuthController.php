<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Pengguna;
use App\Support\PesanValidasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Registrasi, login, dan logout.
 * Kata sandi di-hash otomatis oleh cast 'hashed' pada model Pengguna.
 */
class AuthController extends Controller
{
    public function formLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], PesanValidasi::pesan(), PesanValidasi::atribut());

        if (! Auth::attempt($data, $request->boolean('ingat'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        /** @var Pengguna $pengguna */
        $pengguna = Auth::user();

        return redirect()->intended(route($pengguna->getHalamanUtama()));
    }

    public function formRegistrasi(): View
    {
        return view('auth.register');
    }

    public function registrasi(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:20', 'unique:users,nim'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'regex:/^[0-9+\s\-]{9,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], PesanValidasi::pesan(), PesanValidasi::atribut());

        // Akun yang mendaftar lewat form selalu berperan Mahasiswa.
        $mahasiswa = new Mahasiswa();
        $mahasiswa->setNama($data['name'])
            ->setNim($data['nim'])
            ->setEmail($data['email'])
            ->setNoHp($data['no_hp'] ?? null)
            ->setPassword($data['password']);
        $mahasiswa->save();

        return redirect()->route('login')->with('sukses', 'Akun berhasil dibuat. Silakan masuk.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('sukses', 'Kamu sudah keluar.');
    }
}
