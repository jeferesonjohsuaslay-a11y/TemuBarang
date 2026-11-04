<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Kategori;
use App\Models\LaporanBarang;
use App\Models\LaporanDitemukan;
use App\Models\LaporanHilang;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(KategoriSeeder::class);

        Admin::create([
            'name' => 'Admin',
            'email' => 'admintes@gmail.com',
            'password' => 'password123',
        ]);

        $jeff = Mahasiswa::create(['name' => 'Jeff', 'nim' => '250210500037', 'email' => 'jeffyoi12345@gmail.com', 'no_hp' => '081243487495', 'password' => 'password123']);
        $aat = Mahasiswa::create(['name' => 'aat', 'nim' => '250210500038', 'email' => 'aat12345@gmail.com', 'no_hp' => '081234567802', 'password' => 'password123']);
        $ikhsan = Mahasiswa::create(['name' => 'ikhsan', 'nim' => '250210500039', 'email' => 'ikhsan12345@gmail.com', 'no_hp' => '081234567803', 'password' => 'password123']);

        $k = fn (string $nama) => Kategori::where('nama', 'like', "{$nama}%")->value('id');

        $contoh = [
            [LaporanHilang::class, $jeff, 'Dompet kulit cokelat', 'Dompet kulit cokelat berisi KTM, SIM, dan sedikit uang tunai. Ada gantungan kunci kecil berbentuk bintang.', 'Kantin Fakultas Teknik', 1, 'Dompet', 'hilang'],
            [LaporanDitemukan::class, $aat, 'Kunci motor Honda', 'Kunci motor Honda dengan gantungan karakter kucing berwarna oranye. Sudah dititipkan ke satpam gedung A.', 'Parkiran Gedung A', 1, 'Kunci', 'ditemukan'],
            [LaporanHilang::class, $jeff, 'Earphone nirkabel putih', 'Earphone nirkabel putih dalam case yang ada stiker bunga di bagian atas. Terakhir dipakai saat belajar kelompok.', 'Perpustakaan Lantai 2', 2, 'Ponsel', 'hilang'],
            [LaporanDitemukan::class, $aat, 'Kartu Tanda Mahasiswa a.n. Budi', 'Ditemukan satu KTM tergeletak di dekat mesin fotokopi. Nama dan NIM terbaca jelas di kartu.', 'Gedung Rektorat', 2, 'Kartu', 'ditemukan'],
            [LaporanHilang::class, $ikhsan, 'Jaket hoodie abu-abu', 'Hoodie abu-abu polos ukuran L dengan tulisan kecil di bagian lengan kiri. Tertinggal di bangku taman.', 'Taman Kampus', 3, 'Pakaian', 'hilang'],
            [LaporanDitemukan::class, $jeff, 'Botol minum biru tua', 'Botol minum stainless biru tua dengan stiker sepeda. Ditemukan di meja laboratorium setelah praktikum selesai.', 'Laboratorium Komputer', 3, 'Lainnya', 'ditemukan'],
            [LaporanHilang::class, $jeff, 'Buku catatan Pemrograman Web', 'Buku catatan bersampul hijau berisi catatan kuliah Pemrograman Web dan sketsa desain antarmuka.', 'Ruang Kelas B204', 4, 'Buku', 'selesai'],
            [LaporanDitemukan::class, $ikhsan, 'Tas selempang hitam', 'Tas selempang hitam berisi kalkulator dan alat tulis. Tidak ada identitas pemilik di dalamnya.', 'Masjid Kampus', 5, 'Tas', 'selesai'],
            [LaporanHilang::class, $aat, 'Charger laptop 65W', 'Charger laptop 65W dengan kabel yang dililit pita velcro hitam. Tertinggal di colokan dekat jendela.', 'Perpustakaan Lantai 1', 6, 'Ponsel', 'ditemukan'],
            [LaporanDitemukan::class, $aat, 'Payung lipat motif garis', 'Payung lipat motif garis hijau dan putih, ditemukan di rak sepatu depan ruang kuliah.', 'Gedung Kuliah Bersama', 7, 'Lainnya', 'ditemukan'],
        ];

        foreach ($contoh as [$kelas, $pemilik, $nama, $deskripsi, $lokasi, $hariLalu, $kategori, $status]) {
            $laporan = new $kelas([
                'kategori_id' => $k($kategori),
                'nama_barang' => $nama,
                'deskripsi' => $deskripsi,
                'lokasi' => $lokasi,
                'tanggal_kejadian' => now()->subDays($hariLalu)->toDateString(),
                'kontak' => $pemilik->no_hp,
            ]);
            $laporan->user_id = $pemilik->id;
            $laporan->status = $status;
            $laporan->save();
        }
    }
}
