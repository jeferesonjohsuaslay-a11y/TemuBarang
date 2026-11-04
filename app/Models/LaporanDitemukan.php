<?php

namespace App\Models;

/**
 * Laporan dari penemu barang.
 * Mewarisi LaporanBarang dan mengganti method-nya sesuai konteks "ditemukan".
 */
class LaporanDitemukan extends LaporanBarang
{
    public const JENIS = 'ditemukan';
    public const STATUS_AWAL = self::STATUS_DITEMUKAN;

    /** Laporan temuan tidak punya status "hilang". */
    public function statusYangBolehDipilih(): array
    {
        return [self::STATUS_DITEMUKAN, self::STATUS_SELESAI];
    }

    public function getLabelJenis(): string
    {
        return 'Barang Ditemukan';
    }

    public function getLabelLokasi(): string
    {
        return 'Lokasi ditemukan';
    }

    public function getLabelTanggal(): string
    {
        return 'Tanggal ditemukan';
    }

    public function getLabelPelapor(): string
    {
        return 'Penemu';
    }

    public function getTeksHubungi(): string
    {
        return 'Hubungi penemu';
    }

    public function getPetunjukKontak(): string
    {
        return 'Nomor WhatsApp yang bisa dihubungi pemilik barang.';
    }

    public function getPetunjuk(): string
    {
        return 'Merasa ini barangmu? Hubungi penemunya dan sebutkan ciri barang yang tidak terlihat di foto '
            . '(isi, goresan, atau nomor seri) supaya penemu yakin kamu pemiliknya.';
    }

    /** Deskripsi polimorfik: kalimat ringkas dari sudut pandang penemu. */
    public function getDeskripsi(): string
    {
        return sprintf(
            '%s ditemukan di %s pada %s. %s',
            $this->nama_barang,
            $this->lokasi,
            $this->tanggal_kejadian?->translatedFormat('d F Y'),
            $this->deskripsi
        );
    }
}
