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
                    <li class="page-item disabled"><span class="page-link">&laquo; Trước</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Trước</a>
                    </li>
                @endif

                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                @endphp

                {{-- Trang đầu --}}
                <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>

                {{-- Dấu ... nếu cần --}}
                @if ($currentPage > 3)
                    <li class="page-item"><span class="page-link">...</span></li>
                @endif

                {{-- Các trang gần trang hiện tại --}}
                @for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 1); $i++)
                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Dấu ... nếu cần --}}
                @if ($currentPage < $lastPage - 2)
                    <li class="page-item"><span class="page-link">...</span></li>
                @endif

                {{-- Trang cuối nếu > 1 --}}
                @if ($lastPage > 1)
                    <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                {{-- Nút "Next" --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Tiếp &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled"><span class="page-link">Tiếp &raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
@endif
