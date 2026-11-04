@props(['laporan'])
<a href="{{ route('laporan.show', $laporan) }}" class="kartu {{ $laporan->sudahSelesai() ? 'kartu--selesai' : '' }}">
    <div class="kartu__foto">
        @if ($laporan->urlFoto())
            <img src="{{ $laporan->urlFoto() }}" alt="Foto {{ $laporan->nama_barang }}" loading="lazy">
        @else
            <span class="kartu__ikon" aria-hidden="true">{{ $laporan->kategori->ikon ?? '📦' }}</span>
        @endif
        <span class="label-jenis label-jenis--{{ $laporan->jenis }}">{{ $laporan->getLabelJenis() }}</span>
    </div>
    <div class="kartu__isi">
        <div class="kartu__baris">
            <h3 class="kartu__judul">{{ $laporan->nama_barang }}</h3>
            <x-lencana-status :laporan="$laporan" />
        </div>
        <p class="kartu__ringkas">{{ $laporan->getRingkasan() }}</p>
        <ul class="meta">
            <li><x-icon name="pin" :size="16" /> {{ $laporan->lokasi }}</li>
            <li><x-icon name="calendar" :size="16" /> {{ $laporan->tanggal_kejadian->translatedFormat('d M Y') }}</li>
        </ul>
    </div>
</a>
