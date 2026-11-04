<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\LaporanBarang;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $jumlah = LaporanBarang::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('beranda', [
            'jumlahHilang' => (int) ($jumlah[LaporanBarang::STATUS_HILANG] ?? 0),
            'jumlahDitemukan' => (int) ($jumlah[LaporanBarang::STATUS_DITEMUKAN] ?? 0),
            'jumlahSelesai' => (int) ($jumlah[LaporanBarang::STATUS_SELESAI] ?? 0),
            'kategori' => Kategori::orderBy('id')->get(),
            'terbaru' => LaporanBarang::cariLaporan()->limit(6)->get(),
        ]);
    }
}
