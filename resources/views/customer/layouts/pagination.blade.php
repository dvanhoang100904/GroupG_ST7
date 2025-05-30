@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        {{-- Hiển thị thông tin số mục đang xem và tổng số mục --}}
        <div class="text-muted">
            Hiển thị từ <strong>{{ $paginator->firstItem() }}</strong> đến
            <strong>{{ $paginator->lastItem() }}</strong> trong tổng số
            <strong>{{ $paginator->total() }}</strong> mục
        </div>

        {{-- Phần điều hướng phân trang --}}
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                {{-- Nút "Trước" (Previous) --}}
                @if ($paginator->onFirstPage())
                    {{-- Nếu đang ở trang đầu tiên thì disable nút "Trước" --}}
                    <li class="page-item disabled"><span class="page-link">&laquo; Trước</span></li>
                @else
                    {{-- Ngược lại, cho phép nhấn để về trang trước --}}
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Trước</a>
                    </li>
                @endif

                {{-- Lấy trang hiện tại và trang cuối cùng để sử dụng ở phần sau --}}
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                @endphp

                {{-- Trang đầu tiên --}}
                <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>

                {{-- Hiển thị dấu ... nếu khoảng cách trang hiện tại và trang đầu quá xa --}}
                @if ($currentPage > 3)
                    <li class="page-item"><span class="page-link">...</span></li>
                @endif

                {{-- Hiển thị các trang xung quanh trang hiện tại (tối đa 3 trang) --}}
                @for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 1); $i++)
                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Hiển thị dấu ... nếu khoảng cách trang hiện tại và trang cuối cùng quá xa --}}
                @if ($currentPage < $lastPage - 2)
                    <li class="page-item"><span class="page-link">...</span></li>
                @endif

                {{-- Trang cuối cùng nếu tổng số trang lớn hơn 1 --}}
                @if ($lastPage > 1)
                    <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                {{-- Nút "Tiếp" (Next) --}}
                @if ($paginator->hasMorePages())
                    {{-- Nếu còn trang sau thì cho phép nhấn --}}
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Tiếp &raquo;</a>
                    </li>
                @else
                    {{-- Nếu đã trang cuối cùng thì disable nút "Tiếp" --}}
                    <li class="page-item disabled"><span class="page-link">Tiếp &raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
@endif
