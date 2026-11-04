<?php

namespace App\Support;

/**
 * Pesan validasi berbahasa Indonesia.
 * Dipakai bersama oleh AuthController dan SimpanLaporanRequest
 * sehingga semua pesan error konsisten dan tidak perlu paket bahasa tambahan.
 */
class PesanValidasi
{
    public static function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'min' => ':attribute minimal :min karakter.',
            'email' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah terdaftar.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'date' => 'Format :attribute tidak valid.',
            'before_or_equal' => ':attribute tidak boleh lebih dari hari ini.',
            'exists' => ':attribute yang dipilih tidak tersedia.',
            'in' => ':attribute yang dipilih tidak valid.',
            'regex' => 'Format :attribute tidak valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 3 MB.',
            'foto.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya tidak lebih dari 3 MB.',
        ];
    }

    public static function atribut(): array
    {
        return [
            'name' => 'Nama lengkap',
            'nim' => 'NIM',
            'email' => 'Email',
            'no_hp' => 'Nomor HP',
            'password' => 'Kata sandi',
            'jenis' => 'Jenis laporan',
            'kategori_id' => 'Kategori',
            'nama_barang' => 'Nama barang',
            'deskripsi' => 'Deskripsi',
            'lokasi' => 'Lokasi',
            'tanggal_kejadian' => 'Tanggal',
            'kontak' => 'Kontak',
            'foto' => 'Foto',
            'status' => 'Status',
        ];
    }
}
