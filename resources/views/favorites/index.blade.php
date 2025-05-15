@extends('customer.layouts.app')

@section('title', 'Trang sản phẩm')

@section('content')
<div class="container">
    <h2 class="product-heading">SẢN PHẨM</h2>

    <div class="sort-filter mb-3">
        <div class="sort-label">Sắp xếp:</div>
        <form method="GET" id="sortForm">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <button type="submit" name="sort" value="name_asc" class="sort-btn {{ request('sort') == 'name_asc' ? 'active' : '' }}">Tên A-Z</button>
            <button type="submit" name="sort" value="name_desc" class="sort-btn {{ request('sort') == 'name_desc' ? 'active' : '' }}">Tên Z-A</button>
            <button type="submit" name="sort" value="price_asc" class="sort-btn {{ request('sort') == 'price_asc' ? 'active' : '' }}">Giá thấp đến cao</button>
            <button type="submit" name="sort" value="price_desc" class="sort-btn {{ request('sort') == 'price_desc' ? 'active' : '' }}">Giá cao đến thấp</button>

            @if(request('sort'))
                <button type="submit" name="sort" value="" class="sort-btn reset-btn">Quay lại</button>
            @endif
        </form>
    </div>

    <div class="product-grid">
        @foreach ($products as $product)
            @php
                $isFavorite = auth()->check() && auth()->user()->favorites->contains($product->product_id);
            @endphp

            <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                <div class="product-image">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                </div>

                <div class="product-info">
                    <h3>{{ $product->product_name }}</h3>
                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                    <p class="description">{{ Str::limit($product->description, 100) }}</p>

                    <button class="favorite-btn" data-product-id="{{ $product->product_id }}" aria-label="Toggle favorite" style="background:none; border:none; cursor:pointer;">
                        <i class="fa fa-heart" style="color: {{ $isFavorite ? 'red' : 'black' }}"></i>
                    </button>

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

    @include('customer.layouts.pagination', ['paginator' => $products->appends(request()->query())])

    <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
</div>
@endsection

@push('styles')
<style>
    .favorite-btn i.fa-heart {
        font-size: 24px;
        transition: color 0.3s ease;
    }
    .favorite-btn:hover i.fa-heart {
        color: red;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.favorite-btn').click(function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = btn.find('i.fa-heart');
        let productId = btn.data('product-id');
        let isFavorited = icon.css('color') === 'rgb(255, 0, 0)'; // red

        if (isFavorited) {
            // Xóa yêu thích (DELETE)
            $.ajax({
                url: '/favorites/' + productId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    icon.css('color', 'black'); // đổi sang đen
                    // Bạn có thể show message hoặc xử lý thêm nếu muốn
                },
                error: function() {
                    alert('Xóa yêu thích thất bại. Thử lại nhé!');
                }
            });
        } else {
            // Thêm yêu thích (POST)
            $.post('/favorites/' + productId, {
                _token: '{{ csrf_token() }}'
            }, function() {
                icon.css('color', 'red'); // đổi sang đỏ
            }).fail(function() {
                alert('Thêm yêu thích thất bại. Thử lại nhé!');
            });
        }
    });
    
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("scrollToTopBtn");
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };
        btn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false,
        });
    @endif
</script>
@endpush
