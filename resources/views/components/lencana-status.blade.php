@props(['laporan'])
<span {{ $attributes->merge(['class' => 'lencana lencana--' . $laporan->status]) }}>{{ $laporan->labelStatus() }}</span>
