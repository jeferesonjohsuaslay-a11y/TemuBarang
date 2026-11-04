{{-- Form pencarian & filter. Dipakai di Semua Laporan dan Dashboard Admin. --}}
@php
    $lanjutan = collect($filter)->only(['lokasi', 'dari', 'sampai', 'status'])->filter()->isNotEmpty();
@endphp
<form action="{{ $aksi }}" method="GET" class="filter" role="search">
    <div class="filter__utama">
        <div class="field field--cari">
            <label for="f-q">Nama barang</label>
            <input type="search" id="f-q" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Cari nama atau deskripsi">
        </div>
        <div class="field">
            <label for="f-jenis">Jenis</label>
            <select id="f-jenis" name="jenis">
                <option value="">Semua jenis</option>
                <option value="hilang" @selected(($filter['jenis'] ?? '') === 'hilang')>Barang hilang</option>
                <option value="ditemukan" @selected(($filter['jenis'] ?? '') === 'ditemukan')>Barang ditemukan</option>
            </select>
        </div>
        <div class="field">
            <label for="f-kategori">Kategori</label>
            <select id="f-kategori" name="kategori">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected(($filter['kategori'] ?? '') == $k->id)>{{ $k->ikon }} {{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <details class="filter__lanjutan" @if ($lanjutan) open @endif>
        <summary><x-icon name="filter" :size="18" /> Filter lainnya</summary>
        <div class="filter__grid">
            <div class="field">
                <label for="f-lokasi">Lokasi</label>
                <input type="text" id="f-lokasi" name="lokasi" value="{{ $filter['lokasi'] ?? '' }}" placeholder="Contoh: perpustakaan">
            </div>
            <div class="field">
                <label for="f-status">Status</label>
                <select id="f-status" name="status">
                    <option value="">Semua status</option>
                    <option value="hilang" @selected(($filter['status'] ?? '') === 'hilang')>Hilang</option>
                    <option value="ditemukan" @selected(($filter['status'] ?? '') === 'ditemukan')>Ditemukan</option>
                    <option value="selesai" @selected(($filter['status'] ?? '') === 'selesai')>Selesai</option>
                </select>
            </div>
            <div class="field">
                <label for="f-dari">Tanggal kejadian dari</label>
                <input type="date" id="f-dari" name="dari" value="{{ $filter['dari'] ?? '' }}">
            </div>
            <div class="field">
                <label for="f-sampai">Sampai</label>
                <input type="date" id="f-sampai" name="sampai" value="{{ $filter['sampai'] ?? '' }}">
            </div>
        </div>
    </details>

    <div class="filter__aksi">
        <button type="submit" class="tombol tombol--utama"><x-icon name="search" :size="18" /> Terapkan</button>
        <a href="{{ $aksi }}" class="tombol tombol--ghost">Atur ulang</a>
    </div>
</form>
