{{-- resources/views/setup/partials/pagination.blade.php --}}
{{-- Usage: @include('setup.partials.pagination', ['records' => $records]) --}}

<div class="setup-pagination">
    <span class="pg-info">
        @if($records->total() > 0)
            Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }} record(s)
        @else
            No records found
        @endif
    </span>

    @if($records->lastPage() > 1)
    <div class="pg-links">
        {{-- Prev --}}
        @if($records->onFirstPage())
            <span class="pg-disabled">&laquo;</span>
        @else
            <a href="{{ $records->previousPageUrl() }}">&laquo;</a>
        @endif

        {{-- Pages --}}
        @php
            $current = $records->currentPage();
            $last    = $records->lastPage();
            $range   = collect(range(1, $last));
        @endphp

        @foreach($range as $page)
            @if($page == $current)
                <span class="pg-active">{{ $page }}</span>
            @elseif($page == 1 || $page == $last || abs($page - $current) <= 2)
                <a href="{{ $records->url($page) }}">{{ $page }}</a>
            @elseif(abs($page - $current) == 3)
                <span class="pg-dots">…</span>
            @endif
        @endforeach

        {{-- Next --}}
        @if($records->hasMorePages())
            <a href="{{ $records->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="pg-disabled">&raquo;</span>
        @endif
    </div>
    @endif
</div>
