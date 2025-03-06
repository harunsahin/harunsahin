@props(['items'])

@if ($items->hasPages())
    <div class="d-flex justify-content-center mt-3">
        <nav aria-label="Sayfalama">
            <ul class="pagination">
                {{-- Önceki Sayfa --}}
                @if ($items->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Sayfa Numaraları --}}
                @foreach ($elements as $element)
                    {{-- "Üç Nokta" Ayracı --}}
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Sayfa Numaraları --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $items->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Sonraki Sayfa --}}
                @if ($items->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif 