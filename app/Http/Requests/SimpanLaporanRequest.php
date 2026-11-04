<?php

namespace App\Http\Requests;

use App\Models\LaporanDitemukan;
use App\Models\LaporanHilang;
use App\Support\PesanValidasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi form laporan (buat & ubah). Semua data wajib divalidasi
 * sebelum disimpan agar isi database konsisten.
 */
class SimpanLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // hak akses dicek di controller / middleware
    }

    public function rules(): array
    {
        $aturan = [
            'kategori_id' => ['required', 'exists:kategori,id'],
            'nama_barang' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'lokasi' => ['required', 'string', 'max:150'],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
            'kontak' => ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-]{9,20}$/'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'hapus_foto' => ['nullable', 'boolean'],
        ];

        // Jenis hanya dipilih saat membuat laporan baru.
        if ($this->isMethod('post')) {
            $aturan['jenis'] = ['required', Rule::in([LaporanHilang::JENIS, LaporanDitemukan::JENIS])];
        }

        return $aturan;
    }

    public function messages(): array
    {
        return PesanValidasi::pesan() + [
            'kontak.regex' => 'Kontak harus berupa nomor telepon (9 sampai 20 angka).',
        ];
    }

    public function attributes(): array
    {
        return PesanValidasi::atribut();
    }
}
