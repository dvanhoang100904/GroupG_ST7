@extends('customer.layouts.app')

@section('title', 'Trang sản phẩm')

@section('content')
<div class="container">
    {{-- Tiêu đề cho trang sản phẩm --}}
    <h2 class="product-heading">SẢN PHẨM</h2>

    {{-- Bộ lọc sắp xếp --}}
    <div class="sort-filter mb-3">
        <div class="sort-label">Sắp xếp:</div>
        <form method="GET" id="sortForm">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            {{-- Các button sắp xếp --}}
            <button type="submit" name="sort" value="name_asc" class="sort-btn {{ request('sort') == 'name_asc' ? 'active' : '' }}">Tên A-Z</button>
            <button type="submit" name="sort" value="name_desc" class="sort-btn {{ request('sort') == 'name_desc' ? 'active' : '' }}">Tên Z-A</button>
            <button type="submit" name="sort" value="price_asc" class="sort-btn {{ request('sort') == 'price_asc' ? 'active' : '' }}">Giá thấp đến cao</button>
            <button type="submit" name="sort" value="price_desc" class="sort-btn {{ request('sort') == 'price_desc' ? 'active' : '' }}">Giá cao đến thấp</button>

            {{-- Nút quay lại --}}
            @if(request('sort'))
                <button type="submit" name="sort" value="" class="sort-btn reset-btn">Quay lại</button>
            @endif
        </form>
    </div>


    <div class="product-grid">
        {{-- Lặp qua danh sách sản phẩm --}}
        @foreach ($products as $product)
            {{-- Liên kết đến trang chi tiết sản phẩm dựa trên slug --}}
            <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                <div class="product-image">
                    {{-- Ảnh sản phẩm từ đường dẫn trong DB --}}
                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                </div>

                <div class="product-info">
                    {{-- Tên sản phẩm --}}
                    <h3>{{ $product->product_name }}</h3>

                    {{-- Giá sản phẩm --}}
                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                    {{-- Mô tả --}}
                    <p class="description">{{ Str::limit($product->description, 100) }}</p>

                    {{-- Nút yêu thích --}}
                   <form method="POST" action="{{ route('favorites.store', ['productId' => $product->product_id]) }}" style="display:inline;">
                    @csrf
                    <button class="favorite-btn" data-product-id="{{ $product->product_id }}" style="background:none; border:none; cursor:pointer;">
                        <i class="fa fa-heart" style="color: black;"></i>
                    </button>
                </form>


                    {{-- Nút thêm vào giỏ --}}
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
        <!-- Nút trở về đầu trang -->
    <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("scrollToTopBtn");

        // Ẩn hiện nút khi cuộn
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };

        // Khi nhấn nút thì cuộn lên đầu trang
        btn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>

@endpush
@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.favorite-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let btn = $(this);
        let icon = btn.find('i.fa-heart');
        let productId = btn.data('product-id');

        if (!productId) {
            alert('Không tìm thấy sản phẩm!');
            return;
        }

        let isFavorited = icon.css('color') === 'rgb(255, 0, 0)'; // đỏ

        if (isFavorited) {
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
