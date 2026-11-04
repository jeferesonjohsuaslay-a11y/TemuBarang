<?php

namespace App\Models;

/**
 * Admin mewarisi Pengguna, lalu mengganti (override) beberapa method
 * agar hak aksesnya berbeda dari mahasiswa.
 */
class Admin extends Pengguna
{
    public const PERAN = self::PERAN_ADMIN;

    public function getPeran(): string
    {
        return 'Admin';
    }

    public function isAdmin(): bool
    {
        return true;
    }

    /** Admin boleh mengelola SEMUA laporan. */
    public function bolehMengelola(LaporanBarang $laporan): bool
    {
        return true;
    }

    public function getHalamanUtama(): string
    {
        return 'admin.dashboard';
    }
}
