<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            ['Dompet & Uang', '👛'],
            ['Kartu (KTM/ATM)', '🪪'],
            ['Ponsel & Elektronik', '📱'],
            ['Kunci', '🔑'],
            ['Tas & Aksesori', '🎒'],
            ['Buku & Alat Tulis', '📚'],
            ['Pakaian & Jaket', '🧥'],
            ['Lainnya', '📦'],
        ];

        foreach ($daftar as [$nama, $ikon]) {
            Kategori::updateOrCreate(
                ['slug' => Str::slug($nama)],
                ['nama' => $nama, 'ikon' => $ikon]
            );
        }
    }
}
