<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * KELAS INDUK untuk semua akun (Mahasiswa dan Admin).
 * Konsep OOP yang dipakai di sini:
 *  - Class        : Pengguna adalah cetak biru sebuah akun.
 *  - Constructor  : __construct() mengisi peran awal sesuai kelasnya.
 *  - Inheritance  : Mahasiswa dan Admin mewarisi semua atribut & method ini.
 *  - Polymorphism : isAdmin(), getPeran(), bolehMengelola(), dan getHalamanUtama()
 *                   di-override oleh Admin sehingga hasilnya berbeda.
 * Satu tabel `users` dipakai bersama; kolom `role` menentukan kelas mana
 * yang dibuat saat data dibaca dari database (lihat newFromBuilder).
 */
class Pengguna extends Authenticatable
{
    public const PERAN_MAHASISWA = 'mahasiswa';
    public const PERAN_ADMIN = 'admin';

    /** Peran bawaan kelas ini. Kelas turunan menggantinya. */
    public const PERAN = self::PERAN_MAHASISWA;

    protected $table = 'users';

    // `role` sengaja tidak masuk $fillable agar tidak bisa diisi dari form.
    protected $fillable = ['name', 'nim', 'email', 'no_hp', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    /**
     * Constructor: dijalankan setiap kali objek dibuat dengan `new`.
     * Peran awal diisi otomatis berdasarkan kelasnya (static::PERAN).
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes['role'] = static::PERAN;

        parent::__construct($attributes);
    }

    /**
     * Saat data dibaca dari database, buat objek dari kelas yang sesuai
     * dengan kolom `role` (Admin atau Mahasiswa), bukan selalu Pengguna.
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $data = (array) $attributes;

        $kelas = ($data['role'] ?? null) === self::PERAN_ADMIN
            ? Admin::class
            : Mahasiswa::class;

        $model = (new $kelas)->newInstance([], true);
        $model->setRawAttributes($data, true);
        $model->setConnection($connection ?: $this->getConnectionName());
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    /* ---------------------------------------------------------------
     | Method yang berperilaku BERBEDA di tiap kelas turunan (polymorphism)
     * ------------------------------------------------------------- */

    public function getPeran(): string
    {
        return 'Mahasiswa';
    }

    public function isAdmin(): bool
    {
        return false;
    }

    /** Mahasiswa hanya boleh mengelola laporan miliknya sendiri. */
    public function bolehMengelola(LaporanBarang $laporan): bool
    {
        return $laporan->user_id === $this->id;
    }

    /** Nama route tujuan setelah login berhasil. */
    public function getHalamanUtama(): string
    {
        return 'home';
    }

    /* --------------------------------------------------------------- */

    public function getInisial(): string
    {
        $kata = preg_split('/\s+/', trim($this->name));

        return mb_strtoupper(
            mb_substr($kata[0], 0, 1) . (isset($kata[1]) ? mb_substr($kata[1], 0, 1) : '')
        );
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(LaporanBarang::class, 'user_id');
    }
}
