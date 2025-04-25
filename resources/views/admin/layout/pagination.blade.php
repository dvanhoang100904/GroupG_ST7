@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        {{-- Thông tin hiển thị --}}
        <div class="text-muted">
            Hiển thị từ <strong>{{ $paginator->firstItem() }}</strong> đến
            <strong>{{ $paginator->lastItem() }}</strong> trong tổng số
            <strong>{{ $paginator->total() }}</strong> mục
        </div>

        {{-- Pagination --}}
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">

                {{-- Nút "Previous" --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo; Trước</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Trước</a>
                    </li>
                @endif

                {{-- Các nút trang --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Nút "Next" --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Tiếp &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Tiếp &raquo;</span>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
@endif
