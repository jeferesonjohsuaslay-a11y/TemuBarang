@if ($paginator->hasPages())
    <nav class="paginasi" role="navigation" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="paginasi__item paginasi__item--mati" aria-disabled="true">Sebelumnya</span>
        @else
            <a class="paginasi__item" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="paginasi__item paginasi__item--mati">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $halaman => $url)
                    @if ($halaman == $paginator->currentPage())
                        <span class="paginasi__item paginasi__item--aktif" aria-current="page">{{ $halaman }}</span>
                    @else
                        <a class="paginasi__item" href="{{ $url }}" aria-label="Ke halaman {{ $halaman }}">{{ $halaman }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="paginasi__item" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
        @else
            <span class="paginasi__item paginasi__item--mati" aria-disabled="true">Berikutnya</span>
        @endif
    </nav>
@endif
