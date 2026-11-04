{{--
  Form laporan bersama untuk Buat dan Ubah.
  Variabel: $laporan (objek LaporanHilang/LaporanDitemukan), $kategori, $aksi, $metode, $petaJenis (opsional, hanya saat buat)
--}}
@php $membuat = isset($petaJenis); @endphp

<form method="POST" action="{{ $aksi }}" enctype="multipart/form-data" class="form form--laporan" id="form-laporan"
      @if ($membuat) data-peta="{{ json_encode($petaJenis) }}" @endif novalidate>
    @csrf
    @if ($metode !== 'POST') @method($metode) @endif

    @if ($membuat)
        <fieldset class="pilih-jenis">
            <legend>Jenis laporan</legend>
            @foreach ($petaJenis as $kode => $info)
                <label class="pilih-jenis__opsi pilih-jenis__opsi--{{ $kode }}">
                    <input type="radio" name="jenis" value="{{ $kode }}" @checked(old('jenis', $laporan->getJenis()) === $kode)>
                    <span>
                        <strong>{{ $kode === 'hilang' ? 'Saya kehilangan barang' : 'Saya menemukan barang' }}</strong>
                        <small>{{ $kode === 'hilang' ? 'Barang milikku yang hilang' : 'Barang milik orang lain' }}</small>
                    </span>
                </label>
            @endforeach
        </fieldset>
        @error('jenis') <p class="field__galat">{{ $message }}</p> @enderror
    @endif

    <div class="field">
        <label for="nama_barang">Nama barang</label>
        <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $laporan->nama_barang) }}" maxlength="100" placeholder="Contoh: Dompet kulit cokelat" required
               class="{{ $errors->has('nama_barang') ? 'input--galat' : '' }}">
        @error('nama_barang') <p class="field__galat">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" name="kategori_id" required class="{{ $errors->has('kategori_id') ? 'input--galat' : '' }}">
            <option value="">Pilih kategori</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_id', $laporan->kategori_id) == $k->id)>{{ $k->ikon }} {{ $k->nama }}</option>
            @endforeach
        </select>
        @error('kategori_id') <p class="field__galat">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" required
                  placeholder="Warna, merek, ciri khusus, isi barang. Jangan tulis nomor kartu atau data pribadi yang sensitif."
                  class="{{ $errors->has('deskripsi') ? 'input--galat' : '' }}">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
        @error('deskripsi') <p class="field__galat">{{ $message }}</p> @enderror
    </div>

    <div class="baris-2">
        <div class="field">
            <label for="lokasi" data-label="lokasi">{{ $laporan->getLabelLokasi() }}</label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $laporan->lokasi) }}" maxlength="150" placeholder="Contoh: Perpustakaan lantai 2" required
                   class="{{ $errors->has('lokasi') ? 'input--galat' : '' }}">
            @error('lokasi') <p class="field__galat">{{ $message }}</p> @enderror
        </div>
        <div class="field">
            <label for="tanggal_kejadian" data-label="tanggal">{{ $laporan->getLabelTanggal() }}</label>
            <input type="date" id="tanggal_kejadian" name="tanggal_kejadian" max="{{ now()->toDateString() }}"
                   value="{{ old('tanggal_kejadian', $laporan->tanggal_kejadian?->toDateString() ?? now()->toDateString()) }}" required
                   class="{{ $errors->has('tanggal_kejadian') ? 'input--galat' : '' }}">
            @error('tanggal_kejadian') <p class="field__galat">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="field">
        <label for="kontak">Nomor kontak (WhatsApp)</label>
        <input type="tel" id="kontak" name="kontak" inputmode="tel" placeholder="08xxxxxxxxxx" required
               value="{{ old('kontak', $laporan->kontak ?? auth()->user()->no_hp) }}"
               class="{{ $errors->has('kontak') ? 'input--galat' : '' }}">
        <p class="field__bantu" data-label="kontak">{{ $laporan->getPetunjukKontak() }}</p>
        @error('kontak') <p class="field__galat">{{ $message }}</p> @enderror
    </div>

    <div class="field">
        <label for="foto">Foto <span class="opsional">(opsional)</span></label>
        <div class="unggah" id="area-unggah">
            <div class="unggah__pratinjau" id="pratinjau" @if (! $laporan->urlFoto()) hidden @endif>
                @if ($laporan->urlFoto()) <img src="{{ $laporan->urlFoto() }}" alt="Foto saat ini"> @endif
            </div>
            <div class="unggah__isi">
                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                <p class="field__bantu">JPG, PNG, atau WEBP. Maksimal 3 MB.</p>
                @if ($laporan->urlFoto())
                    <label class="centang"><input type="checkbox" name="hapus_foto" value="1"> Hapus foto saat ini</label>
                @endif
            </div>
        </div>
        @error('foto') <p class="field__galat">{{ $message }}</p> @enderror
    </div>

    <div class="form__aksi">
        <button type="submit" class="tombol tombol--utama">{{ $membuat ? 'Kirim laporan' : 'Simpan perubahan' }}</button>
        <a href="{{ $batal }}" class="tombol tombol--ghost">Batal</a>
    </div>
</form>
