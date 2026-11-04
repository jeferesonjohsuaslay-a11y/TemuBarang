@extends('layouts.app')
@section('judul', 'Laporan saya')

@section('isi')
    <div class="judul-halaman">
        <div>
            <h1>Laporan saya</h1>
            <p>{{ $laporan->count() }} laporan. Ubah, tandai selesai, atau hapus laporan milikmu di sini.</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="tombol tombol--utama"><x-icon name="plus" :size="18" /> Buat laporan</a>
    </div>

    @if ($laporan->isEmpty())
        <div class="kosong">
            <x-icon name="inbox" :size="40" />
            <h3>Kamu belum membuat laporan</h3>
            <p>Kehilangan atau menemukan barang di kampus? Buat laporan pertamamu sekarang.</p>
            <a href="{{ route('laporan.create') }}" class="tombol tombol--utama">Buat laporan</a>
        </div>
    @else
        <div class="tabel-bungkus">
            <table class="tabel">
                <thead>
                    <tr><th>Barang</th><th>Jenis</th><th>Status</th><th>Tanggal</th><th class="kanan">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $item)
                        <tr>
                            <td data-label="Barang">
                                <a href="{{ route('laporan.show', $item) }}" class="sel-barang">
                                    <span class="mini-foto">
                                        @if ($item->urlFoto()) <img src="{{ $item->urlFoto() }}" alt=""> @else {{ $item->kategori->ikon ?? '📦' }} @endif
                                    </span>
                                    <span><strong>{{ $item->nama_barang }}</strong><small>{{ $item->lokasi }}</small></span>
                                </a>
                            </td>
                            <td data-label="Jenis"><span class="label-jenis label-jenis--{{ $item->jenis }} label-jenis--inline">{{ $item->getLabelJenis() }}</span></td>
                            <td data-label="Status"><x-lencana-status :laporan="$item" /></td>
                            <td data-label="Tanggal">{{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</td>
                            <td class="kanan">
                                <div class="aksi-baris">
                                    <a href="{{ route('laporan.show', $item) }}" class="tombol tombol--ghost tombol--kecil">Lihat</a>
                                    <a href="{{ route('laporan.edit', $item) }}" class="tombol tombol--ghost tombol--kecil">Ubah</a>
                                    @unless ($item->sudahSelesai())
                                        <form method="POST" action="{{ route('laporan.status', $item) }}"
                                              data-konfirmasi="Tandai &quot;{{ $item->nama_barang }}&quot; sebagai selesai?" data-tombol="Ya, tandai selesai" data-gaya="utama">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button class="tombol tombol--ghost tombol--kecil">Selesai</button>
                                        </form>
                                    @endunless
                                    <form method="POST" action="{{ route('laporan.destroy', $item) }}"
                                          data-konfirmasi="Hapus laporan &quot;{{ $item->nama_barang }}&quot;? Laporan yang dihapus tidak bisa dikembalikan." data-tombol="Ya, hapus">
                                        @csrf @method('DELETE')
                                        <button class="tombol tombol--bahaya-garis tombol--kecil">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
