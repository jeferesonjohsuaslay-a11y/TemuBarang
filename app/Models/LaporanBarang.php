<?php

namespace App\Models;

use App\Contracts\DapatDideskripsikan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * KELAS INDUK untuk laporan barang.
 * Jangan dibuat langsung; gunakan LaporanHilang atau LaporanDitemukan.
 *
 * Konsep OOP yang dipakai di sini:
 *  - Class        : cetak biru satu laporan (atribut + perilaku).
 *  - Constructor  : __construct() mengisi jenis dan status awal.
 *  - Method       : cariLaporan(), ubahStatus(), tandaiSelesai(), dan lainnya.
 *  - Inheritance  : LaporanHilang dan LaporanDitemukan mewarisi kelas ini.
 *  - Polymorphism : getDeskripsi() dan method label di-override tiap turunan.
 */
class LaporanBarang extends Model implements DapatDideskripsikan
{
    public const STATUS_HILANG = 'hilang';
    public const STATUS_DITEMUKAN = 'ditemukan';
    public const STATUS_SELESAI = 'selesai';

    /** Diganti oleh kelas turunan. */
    public const JENIS = 'hilang';
    public const STATUS_AWAL = self::STATUS_HILANG;

    protected $table = 'laporan_barang';

    // user_id, jenis, dan status sengaja tidak boleh diisi dari form.
    protected $fillable = [
        'kategori_id', 'nama_barang', 'deskripsi',
        'lokasi', 'tanggal_kejadian', 'foto', 'kontak',
    ];

    protected $casts = ['tanggal_kejadian' => 'date'];

    /**
     * Constructor: nilai awal jenis & status ditentukan oleh kelas
     * turunan (static::JENIS dan static::STATUS_AWAL), lalu atribut
     * dari form (nama barang, lokasi, tanggal, dst.) diisi oleh parent.
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = [
            'jenis' => static::JENIS,
            'status' => static::STATUS_AWAL,
        ];

        parent::__construct($attributes);
    }

    /* ---------------------------------------------------------------
     | Memilih kelas turunan yang tepat
     * ------------------------------------------------------------- */

    public static function kelasUntukJenis(?string $jenis): string
    {
        return $jenis === LaporanDitemukan::JENIS
            ? LaporanDitemukan::class
            : LaporanHilang::class;
    }

    /** Saat dibaca dari database, buat objek sesuai kolom `jenis`. */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $data = (array) $attributes;
        $kelas = static::kelasUntukJenis($data['jenis'] ?? null);

        $model = (new $kelas)->newInstance([], true);
        $model->setRawAttributes($data, true);
        $model->setConnection($connection ?: $this->getConnectionName());
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    /** Data label kedua jenis laporan, dipakai form agar teksnya ikut berubah. */
    public static function petaJenis(): array
    {
        $peta = [];

        foreach ([LaporanHilang::class, LaporanDitemukan::class] as $kelas) {
            $contoh = new $kelas;
            $peta[$kelas::JENIS] = [
                'label' => $contoh->getLabelJenis(),
                'lokasi' => $contoh->getLabelLokasi(),
                'tanggal' => $contoh->getLabelTanggal(),
                'kontak' => $contoh->getPetunjukKontak(),
            ];
        }

        return $peta;
    }

    /* ---------------------------------------------------------------
     | Relasi
     * ------------------------------------------------------------- */

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /* ---------------------------------------------------------------
     | Method pencarian & pengelolaan
     * ------------------------------------------------------------- */

    /**
     * Mencari laporan berdasarkan nama/kata kunci, jenis, kategori,
     * status, lokasi, dan rentang tanggal kejadian.
     */
    public static function cariLaporan(array $filter = []): Builder
    {
        return static::query()
            ->with(['kategori', 'pelapor'])
            ->when($filter['q'] ?? null, function ($query, $kata) {
                $query->where(function ($w) use ($kata) {
                    $w->where('nama_barang', 'like', "%{$kata}%")
                      ->orWhere('deskripsi', 'like', "%{$kata}%");
                });
            })
            ->when($filter['jenis'] ?? null, fn ($q, $v) => $q->where('jenis', $v))
            ->when($filter['kategori'] ?? null, fn ($q, $v) => $q->where('kategori_id', $v))
            ->when($filter['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filter['lokasi'] ?? null, fn ($q, $v) => $q->where('lokasi', 'like', "%{$v}%"))
            ->when($filter['dari'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_kejadian', '>=', $v))
            ->when($filter['sampai'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_kejadian', '<=', $v))
            ->latest();
    }

    /** Status yang boleh dipilih untuk laporan ini (berbeda per jenis). */
    public function statusYangBolehDipilih(): array
    {
        return [self::STATUS_HILANG, self::STATUS_DITEMUKAN, self::STATUS_SELESAI];
    }

    public function ubahStatus(string $statusBaru): bool
    {
        if (! in_array($statusBaru, $this->statusYangBolehDipilih(), true)) {
            throw new InvalidArgumentException("Status '{$statusBaru}' tidak valid untuk laporan ini.");
        }

        $this->status = $statusBaru;

        return $this->save();
    }

    public function tandaiSelesai(): bool
    {
        return $this->ubahStatus(self::STATUS_SELESAI);
    }

    public function sudahSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function labelStatus(): string
    {
        return match ($this->status) {
            self::STATUS_HILANG => 'Hilang',
            self::STATUS_DITEMUKAN => 'Ditemukan',
            self::STATUS_SELESAI => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    /** Hapus file foto (jika ada) lalu hapus laporan dari database. */
    public function hapusLaporan(): bool
    {
        $this->hapusFoto();

        return (bool) $this->delete();
    }

    public function hapusFoto(): void
    {
        if ($this->foto) {
            Storage::disk('public')->delete($this->foto);
            $this->foto = null;
        }
    }

    public function urlFoto(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function getRingkasan(int $batas = 90): string
    {
        return Str::limit($this->deskripsi ?? '', $batas);
    }

    /** Ubah nomor kontak menjadi format WhatsApp (62xxxxxxxx), atau null. */
    public function nomorWhatsapp(): ?string
    {
        $angka = preg_replace('/\D+/', '', $this->kontak ?? '');

        if (strlen($angka) < 9) {
            return null;
        }

        if (str_starts_with($angka, '0')) {
            return '62' . substr($angka, 1);
        }

        if (str_starts_with($angka, '62')) {
            return $angka;
        }

        return '62' . $angka;
    }

    /* ---------------------------------------------------------------
     | Method yang di-OVERRIDE oleh kelas turunan (polymorphism).
     | Isi di bawah ini hanya nilai bawaan untuk kelas induk.
     * ------------------------------------------------------------- */

    public function getJenis(): string
    {
        return static::JENIS;
    }

    public function getLabelJenis(): string
    {
        return 'Laporan';
    }

    public function getLabelLokasi(): string
    {
        return 'Lokasi';
    }

    public function getLabelTanggal(): string
    {
        return 'Tanggal kejadian';
    }

    public function getLabelPelapor(): string
    {
        return 'Pelapor';
    }

    public function getTeksHubungi(): string
    {
        return 'Hubungi pelapor';
    }

    public function getPetunjukKontak(): string
    {
        return 'Nomor yang bisa dihubungi.';
    }

    public function getPetunjuk(): string
    {
        return '';
    }

    public function getDeskripsi(): string
    {
        return (string) $this->deskripsi;
    }
}
