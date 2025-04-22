@if ($orders->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        {{-- Thông tin hiển thị --}}
        <div class="text-muted">
            Hiển thị từ <strong>{{ $orders->firstItem() }}</strong> đến
            <strong>{{ $orders->lastItem() }}</strong> trong tổng số
            <strong>{{ $orders->total() }}</strong> đơn hàng
        </div>

        {{-- Pagination --}}
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">

                {{-- Nút "Previous" --}}
                @if ($orders->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo; Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                    </li>
                @endif

                {{-- Các nút trang --}}
                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if ($page == $orders->currentPage())
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
                @if ($orders->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Next &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Next &raquo;</span>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
@endif
