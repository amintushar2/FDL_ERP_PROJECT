@if ($paginator->hasPages())
<div class="pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-item disabled">« Prev</span>
    @else
        <a class="page-item" href="{{ $paginator->previousPageUrl() }}">« Prev</a>
    @endif

    {{-- First few pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-item disabled">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-item active">{{ $page }}</span>
                @else
                    <a class="page-item" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a class="page-item" href="{{ $paginator->nextPageUrl() }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div>
@endif
