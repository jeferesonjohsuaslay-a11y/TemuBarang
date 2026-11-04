<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanLaporanRequest;
use App\Models\Kategori;
use App\Models\LaporanBarang;
use App\Models\LaporanDitemukan;
use App\Models\LaporanHilang;
use App\Support\PesanValidasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Seluruh alur laporan: daftar + pencarian, buat, lihat, ubah, hapus,
 * dan ubah status. Pengecekan hak akses memakai method polimorfik
 * bolehMengelola() milik Mahasiswa / Admin.
 */
class LaporanController extends Controller
{
    /** Semua laporan + pencarian dan filter. */
    public function index(Request $request): View
    {
        $filter = $request->only(['q', 'jenis', 'kategori', 'status', 'lokasi', 'dari', 'sampai']);

        return view('laporan.index', [
            'laporan' => LaporanBarang::cariLaporan($filter)->paginate(9)->withQueryString(),
            'kategori' => Kategori::orderBy('id')->get(),
            'filter' => $filter,
        ]);
    }

    public function create(Request $request): View
    {
        $kelas = LaporanBarang::kelasUntukJenis($request->query('jenis'));

        return view('laporan.create', [
            'laporan' => new $kelas,
            'kategori' => Kategori::orderBy('id')->get(),
            'petaJenis' => LaporanBarang::petaJenis(),
        ]);
    }

    public function store(SimpanLaporanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Kelas yang dibuat (LaporanHilang / LaporanDitemukan) mengikuti pilihan jenis.
        $kelas = LaporanBarang::kelasUntukJenis($data['jenis']);
        $laporan = new $kelas($data);
        $laporan->user_id = $request->user()->id;

        if ($request->hasFile('foto')) {
            $laporan->foto = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->save();

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('sukses', 'Laporan berhasil dibuat.');
    }

    public function show(LaporanBarang $laporan): View
    {
        $laporan->load(['kategori', 'pelapor']);

        return view('laporan.show', ['laporan' => $laporan]);
    }

    /** Kelola Laporan Saya. */
    public function saya(Request $request): View
    {
        $laporan = $request->user()
            ->laporan()
            ->with('kategori')
            ->latest()
            ->get();

        return view('laporan.saya', ['laporan' => $laporan]);
    }

    public function edit(Request $request, LaporanBarang $laporan): View
    {
        $this->pastikanBolehMengelola($request, $laporan);

        return view('laporan.edit', [
            'laporan' => $laporan,
            'kategori' => Kategori::orderBy('id')->get(),
        ]);
    }

    public function update(SimpanLaporanRequest $request, LaporanBarang $laporan): RedirectResponse
    {
        $this->pastikanBolehMengelola($request, $laporan);

        $laporan->fill($request->validated());

        if ($request->hasFile('foto')) {
            $laporan->hapusFoto();
            $laporan->foto = $request->file('foto')->store('laporan', 'public');
        } elseif ($request->boolean('hapus_foto')) {
            $laporan->hapusFoto();
        }

        $laporan->save();

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('sukses', 'Perubahan laporan disimpan.');
    }

    public function ubahStatus(Request $request, LaporanBarang $laporan): RedirectResponse
    {
        $this->pastikanBolehMengelola($request, $laporan);

        $data = $request->validate([
            'status' => ['required', Rule::in($laporan->statusYangBolehDipilih())],
        ], PesanValidasi::pesan(), PesanValidasi::atribut());

        $laporan->ubahStatus($data['status']);

        return back()->with('sukses', 'Status laporan diperbarui menjadi ' . $laporan->labelStatus() . '.');
    }

    public function destroy(Request $request, LaporanBarang $laporan): RedirectResponse
    {
        $this->pastikanBolehMengelola($request, $laporan);

        $laporan->hapusLaporan();

        // Admin kembali ke dashboard, mahasiswa kembali ke Laporan Saya.
        $tujuan = $request->user()->isAdmin() ? 'admin.dashboard' : 'laporan.saya';

        return redirect()->route($tujuan)->with('sukses', 'Laporan berhasil dihapus.');
    }

    private function pastikanBolehMengelola(Request $request, LaporanBarang $laporan): void
    {
        abort_unless($request->user()->bolehMengelola($laporan), 403);
    }
}
