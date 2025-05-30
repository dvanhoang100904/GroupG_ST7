@extends('customer.layouts.app')

{{-- Đặt tiêu đề trang theo tên danh mục --}}
@section('title', $category->category_name)

@section('content')
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- Hiển thị tên danh mục sản phẩm (viết hoa toàn bộ) --}}
        <h2 class="product-heading">{{ mb_strtoupper($category->category_name) }}</h2>

        {{-- Bộ lọc sắp xếp sản phẩm --}}
        <div class="sort-filter mb-3">
            <label class="sort-label" for="sortForm">Sắp xếp:</label>

            {{-- Form sắp xếp sản phẩm theo tên hoặc giá --}}
            <form method="GET" id="sortForm" action="{{ route('category.show', $category->slug) }}">
                {{-- Giữ lại từ khóa tìm kiếm nếu có --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Các nút lọc --}}
                <button type="submit" name="sort" value="name_asc" class="sort-btn {{ request('sort') == 'name_asc' ? 'active' : '' }}">Tên A-Z</button>
                <button type="submit" name="sort" value="name_desc" class="sort-btn {{ request('sort') == 'name_desc' ? 'active' : '' }}">Tên Z-A</button>
                <button type="submit" name="sort" value="price_asc" class="sort-btn {{ request('sort') == 'price_asc' ? 'active' : '' }}">Giá thấp đến cao</button>
                <button type="submit" name="sort" value="price_desc" class="sort-btn {{ request('sort') == 'price_desc' ? 'active' : '' }}">Giá cao đến thấp</button>

                {{-- Nút quay lại nếu đã chọn bộ lọc --}}
                @if(request('sort'))
                    <button type="submit" name="sort" value="" class="sort-btn reset-btn">Quay lại</button>
                @endif
            </form>
        </div>

        {{-- Hiển thị danh sách sản phẩm --}}
        <div class="product-grid">
            {{-- Vòng lặp qua các sản phẩm (Lưu ý: trùng tên biến $product có thể gây nhầm lẫn nếu biến này được dùng ở nơi khác) --}}
            @forelse($products as $product)
                <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                    <div class="product-image">
                        {{-- Ảnh sản phẩm --}}
                        <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                    </div>

                    <div class="product-info">
                        {{-- Tên sản phẩm --}}
                        <h3>{{ $product->product_name }}</h3>
                        {{-- Giá sản phẩm --}}
                        <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                        {{-- Mô tả ngắn gọn --}}
                        <p class="description">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>

                        {{-- Nút yêu thích (hiện biểu tượng trái tim) --}}
                        <button class="like-btn">❤</button>

                        {{-- Form thêm vào giỏ hàng --}}
                        <form method="POST" action="{{ route('cart.addToCart') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}" />
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                </a>
            @empty
                {{-- Nếu không có sản phẩm nào trong danh mục --}}
                <p>Không có sản phẩm nào trong danh mục này.</p>
            @endforelse
        </div>

        {{-- Hiển thị phân trang nếu có --}}
        @if ($products instanceof \Illuminate\Pagination\Paginator || $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @include('customer.layouts.pagination', ['paginator' => $products])
        @endif
    </div>

    {{-- Nút trở về đầu trang --}}
    <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
@endsection

{{-- Script điều khiển nút cuộn lên đầu trang --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("scrollToTopBtn");

        // Hiển thị nút khi cuộn xuống
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };

        // Khi nhấn thì cuộn về đầu trang
        btn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endpush
