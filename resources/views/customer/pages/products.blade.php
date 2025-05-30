@extends('customer.layouts.app') {{-- Kế thừa layout giao diện khách hàng --}}

@section('title', 'Trang sản phẩm') {{-- Gán tiêu đề trang --}}

@section('content') {{-- Bắt đầu nội dung trang --}}
<div class="container">

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    {{-- Tiêu đề cho trang sản phẩm --}}
    <h2 class="product-heading">SẢN PHẨM</h2>

    {{-- Bộ lọc sắp xếp --}}
    <div class="sort-filter mb-3">
        <div class="sort-label">Sắp xếp:</div>
        <form method="GET" id="sortForm"> {{-- Form sắp xếp sử dụng phương thức GET --}}
            @if(request('search'))
                {{-- Giữ nguyên từ khóa tìm kiếm nếu có --}}
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            {{-- Các button sắp xếp theo tên và giá --}}
            <button type="submit" name="sort" value="name_asc" class="sort-btn {{ request('sort') == 'name_asc' ? 'active' : '' }}">Tên A-Z</button>
            <button type="submit" name="sort" value="name_desc" class="sort-btn {{ request('sort') == 'name_desc' ? 'active' : '' }}">Tên Z-A</button>
            <button type="submit" name="sort" value="price_asc" class="sort-btn {{ request('sort') == 'price_asc' ? 'active' : '' }}">Giá thấp đến cao</button>
            <button type="submit" name="sort" value="price_desc" class="sort-btn {{ request('sort') == 'price_desc' ? 'active' : '' }}">Giá cao đến thấp</button>

            {{-- Nút quay lại để xóa bộ lọc --}}
            @if(request('sort'))
                <button type="submit" name="sort" value="" class="sort-btn reset-btn">Quay lại</button>
            @endif
        </form>
    </div>

    <div class="product-grid">
        {{-- Nếu không có sản phẩm nào --}}
        @if ($products->count() === 0)
            <div class="alert alert-warning text-center my-4">
                Không tìm thấy sản phẩm nào{{ $search ? ' với từ khóa: "' . $search . '"' : '' }}.
            </div>
        @endif

        {{-- Lặp qua danh sách sản phẩm --}}
        @foreach ($products as $product)
            {{-- Thẻ bao mỗi sản phẩm, liên kết tới trang chi tiết --}}
            <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                {{-- Hiển thị ảnh sản phẩm --}}
                <div class="product-image">
                    @php
                        $imagePath = public_path($product->image);
                        $categorySlug = \Illuminate\Support\Str::slug(optional($product->category)->category_name ?? 'mac-dinh');
                        $defaultImage = "images/{$categorySlug}/mac-dinh.jpg";
                    @endphp

                    @if ($product->image && file_exists($imagePath))
                        <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                    @elseif (file_exists(public_path($defaultImage)))
                        <img src="{{ asset($defaultImage) }}" alt="Ảnh mặc định">
                    @else
                        <p class="text-muted">Không có ảnh</p>
                    @endif
                </div>

                <div class="product-info">
                    {{-- Tên sản phẩm --}}
                    <h3>{{ $product->product_name }}</h3>

                    {{-- Giá sản phẩm được định dạng --}}
                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                    {{-- Mô tả rút gọn sản phẩm --}}
                    <p class="description">{{ Str::limit($product->description, 100) }}</p>

                    {{-- Form yêu thích sản phẩm --}}
                    <form method="POST" action="{{ route('favorites.store', ['productId' => $product->product_id]) }}" style="display:inline;">
                        @csrf
                        <button class="favorite-btn" data-product-id="{{ $product->product_id }}" style="background:none; border:none; cursor:pointer;">
                            <i class="fa fa-heart" style="color: black;"></i> {{-- Icon trái tim --}}
                        </button>
                    </form>

                    {{-- Form thêm vào giỏ hàng --}}
                    <form method="POST" action="{{ route('cart.addToCart') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}" />
                        <input type="hidden" name="quantity" value="1" />
                        <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                    </form>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Phân trang --}}
    @include('customer.layouts.pagination', ['paginator' => $products->appends(request()->query())])

    {{-- Nút cuộn về đầu trang --}}
    <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
</div>
@endsection

@push('scripts') {{-- Đẩy script vào cuối trang --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("scrollToTopBtn");

        // Hiện nút khi cuộn trang xuống dưới 200px
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };

        // Cuộn lên đầu trang khi nhấn nút
        btn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endpush

@push('scripts') {{-- Script xử lý yêu thích sản phẩm --}}
<script>
$(document).ready(function() {
    $(document).on('click', '.favorite-btn', function(e) {
        e.preventDefault(); // Ngăn hành vi mặc định của nút
        e.stopPropagation(); // Ngăn click lan ra thẻ cha (thẻ <a>)

        let btn = $(this);
        let icon = btn.find('i.fa-heart');
        let productId = btn.data('product-id');

        if (!productId) {
            alert('Không tìm thấy sản phẩm!');
            return;
        }

        let isFavorited = icon.css('color') === 'rgb(255, 0, 0)'; // Kiểm tra màu đỏ

        if (isFavorited) {
            // Nếu đã yêu thích thì xóa
            $.ajax({
                url: '/favorites/' + productId,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function() {
                    icon.css('color', 'black');
                },
                error: function() {
                    alert('Xóa yêu thích thất bại. Thử lại nhé!');
                }
            });
        } else {
            // Nếu chưa yêu thích thì thêm mới
            $.post('/favorites/' + productId, {_token: '{{ csrf_token() }}'})
            .done(function() {
                icon.css('color', 'red');
            })
            .fail(function() {
                alert('Thêm yêu thích thất bại. Thử lại nhé!');
            });
        }
    });
});
</script>
@endpush
