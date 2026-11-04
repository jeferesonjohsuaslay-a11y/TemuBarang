<?php

namespace App\Models;

/**
 * Mahasiswa mewarisi Pengguna.
 * Perilakunya sama dengan induk (hanya boleh mengelola laporan sendiri),
 * jadi tidak perlu meng-override apa pun selain peran awalnya.
 */
class Mahasiswa extends Pengguna
{
    public const PERAN = self::PERAN_MAHASISWA;
}
