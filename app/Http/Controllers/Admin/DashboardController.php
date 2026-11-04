<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LaporanBarang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->only(['q', 'jenis', 'kategori', 'status', 'lokasi', 'dari', 'sampai']);

        $jumlah = LaporanBarang::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', [
            'laporan' => LaporanBarang::cariLaporan($filter)->paginate(10)->withQueryString(),
            'kategori' => Kategori::orderBy('id')->get(),
            'filter' => $filter,
            'jumlahHilang' => (int) ($jumlah[LaporanBarang::STATUS_HILANG] ?? 0),
            'jumlahDitemukan' => (int) ($jumlah[LaporanBarang::STATUS_DITEMUKAN] ?? 0),
            'jumlahSelesai' => (int) ($jumlah[LaporanBarang::STATUS_SELESAI] ?? 0),
            'jumlahPengguna' => Mahasiswa::where('role', Mahasiswa::PERAN)->count(),
        ]);
    }
}
