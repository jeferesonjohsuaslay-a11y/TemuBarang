@extends('layouts.app')
@section('judul', $laporan->nama_barang)

@section('isi')
    <a href="{{ route('laporan.index') }}" class="kembali"><x-icon name="arrow-left" :size="18" /> Semua laporan</a>

    <article class="detail">
        <div class="detail__foto">
            @if ($laporan->urlFoto())
                <img src="{{ $laporan->urlFoto() }}" alt="Foto {{ $laporan->nama_barang }}">
            @else
                <div class="detail__tanpa-foto">
                    <span aria-hidden="true">{{ $laporan->kategori->ikon ?? '📦' }}</span>
                    <p>Pelapor tidak melampirkan foto</p>
                </div>
            @endif
        </div>

        <div class="detail__isi">
            <div class="detail__label">
                <span class="label-jenis label-jenis--{{ $laporan->jenis }} label-jenis--inline">{{ $laporan->getLabelJenis() }}</span>
                <x-lencana-status :laporan="$laporan" />
            </div>

            <h1>{{ $laporan->nama_barang }}</h1>
            <p class="detail__lead">{{ $laporan->getDeskripsi() }}</p>

            <dl class="info">
                <div><dt><x-icon name="package" :size="18" /> Kategori</dt><dd>{{ $laporan->kategori->ikon ?? '' }} {{ $laporan->kategori->nama ?? '-' }}</dd></div>
                <div><dt><x-icon name="pin" :size="18" /> {{ $laporan->getLabelLokasi() }}</dt><dd>{{ $laporan->lokasi }}</dd></div>
                <div><dt><x-icon name="calendar" :size="18" /> {{ $laporan->getLabelTanggal() }}</dt><dd>{{ $laporan->tanggal_kejadian->translatedFormat('l, d F Y') }}</dd></div>
                <div><dt><x-icon name="user" :size="18" /> Dilaporkan</dt><dd>{{ $laporan->created_at->diffForHumans() }}</dd></div>
            </dl>

            <section class="kontak" aria-labelledby="judul-kontak">
                <h2 id="judul-kontak">{{ $laporan->getLabelPelapor() }}</h2>
                <p class="kontak__nama">{{ $laporan->pelapor->name ?? 'Pengguna' }}</p>
                <p class="kontak__nomor"><x-icon name="phone" :size="18" /> {{ $laporan->kontak }}</p>

                @if ($laporan->sudahSelesai())
                    <p class="kontak__selesai"><x-icon name="check-circle" :size="18" /> Laporan ini sudah selesai.</p>
                @else
                    @if ($laporan->nomorWhatsapp())
                        <a class="tombol tombol--wa" target="_blank" rel="noopener"
                           href="https://wa.me/{{ $laporan->nomorWhatsapp() }}?text={{ rawurlencode('Halo, saya melihat laporan "' . $laporan->nama_barang . '" di TemuBarang.') }}">
                            <x-icon name="message" :size="18" /> {{ $laporan->getTeksHubungi() }} lewat WhatsApp
                        </a>
                    @endif
                    <p class="kontak__petunjuk">{{ $laporan->getPetunjuk() }}</p>
                @endif
            </section>

            @if (auth()->user()->bolehMengelola($laporan))
                <section class="kelola" aria-labelledby="judul-kelola">
                    <h2 id="judul-kelola">Kelola laporan</h2>
                    <div class="kelola__aksi">
                        <a href="{{ route('laporan.edit', $laporan) }}" class="tombol tombol--ghost tombol--kecil"><x-icon name="edit" :size="16" /> Ubah</a>

                        @if ($laporan->sudahSelesai())
                            <form method="POST" action="{{ route('laporan.status', $laporan) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $laporan::STATUS_AWAL }}">
                                <button class="tombol tombol--ghost tombol--kecil"><x-icon name="undo" :size="16" /> Buka kembali</button>
                            </form>
                        @else
                            @if ($laporan->jenis === 'hilang' && $laporan->status === 'hilang')
                                <form method="POST" action="{{ route('laporan.status', $laporan) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ditemukan">
                                    <button class="tombol tombol--ghost tombol--kecil"><x-icon name="eye" :size="16" /> Barang sudah ditemukan</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('laporan.status', $laporan) }}"
                                  data-konfirmasi="Tandai laporan ini selesai? Gunakan ini kalau barang sudah kembali ke pemiliknya." data-tombol="Ya, tandai selesai" data-gaya="utama">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <button class="tombol tombol--utama tombol--kecil"><x-icon name="check" :size="16" /> Tandai selesai</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('laporan.destroy', $laporan) }}"
                              data-konfirmasi="Hapus laporan &quot;{{ $laporan->nama_barang }}&quot;? Laporan yang dihapus tidak bisa dikembalikan." data-tombol="Ya, hapus">
                            @csrf @method('DELETE')
                            <button class="tombol tombol--bahaya-garis tombol--kecil"><x-icon name="trash" :size="16" /> Hapus</button>
                        </form>
                    </div>
                </section>
            @endif
        </div>
    </article>
@endsection
