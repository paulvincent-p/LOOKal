@if ($paginator->hasPages())
<nav class="pager" role="navigation" aria-label="Pagination">
  @if ($paginator->onFirstPage())
    <span class="btn disabled">Previous</span>
  @else
    <a class="btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
  @endif
  <span>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
  @if ($paginator->hasMorePages())
    <a class="btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
  @else
    <span class="btn disabled">Next</span>
  @endif
</nav>
@endif
