<?php

namespace App\Contracts;

/**
 * Kontrak (interface) untuk objek yang bisa menjelaskan dirinya sendiri.
 *
 * Dipakai untuk POLYMORPHISM: LaporanHilang dan LaporanDitemukan sama-sama
 * memenuhi kontrak ini, tetapi isi getDeskripsi() masing-masing berbeda.
 */
interface DapatDideskripsikan
{
    public function getDeskripsi(): string;
}
