<?php

namespace App\Models;

/**
 * Laporan dari pemilik yang kehilangan barang.
 * Mewarisi LaporanBarang dan mengganti method-nya sesuai konteks "hilang".
 */
class LaporanHilang extends LaporanBarang
{
    public const JENIS = 'hilang';
    public const STATUS_AWAL = self::STATUS_HILANG;

    public function getLabelJenis(): string
    {
        return 'Barang Hilang';
    }

    public function getLabelLokasi(): string
    {
        return 'Lokasi terakhir terlihat';
    }

    public function getLabelTanggal(): string
    {
        return 'Tanggal hilang';
    }

    public function getLabelPelapor(): string
    {
        return 'Pemilik barang';
    }

    public function getTeksHubungi(): string
    {
        return 'Hubungi pemilik';
    }

    public function getPetunjukKontak(): string
    {
        return 'Nomor WhatsApp yang bisa dihubungi penemu.';
    }

    public function getPetunjuk(): string
    {
        return 'Pernah melihat barang ini? Hubungi pemiliknya lewat kontak di samping. '
            . 'Kalau barangnya sudah kamu amankan, buat juga Laporan Temuan agar mudah dicocokkan.';
    }

    /** Deskripsi polimorfik: kalimat ringkas dari sudut pandang pemilik. */
    public function getDeskripsi(): string
    {
        return sprintf(
            '%s hilang di %s pada %s. %s',
            $this->nama_barang,
            $this->lokasi,
            $this->tanggal_kejadian?->translatedFormat('d F Y'),
            $this->deskripsi
        );
    }
}
