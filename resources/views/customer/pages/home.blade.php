@extends('customer.layouts.app')
{{-- Kế thừa layout giao diện chung --}}

@section('content')
    {{-- Bắt đầu nội dung chính sẽ được chèn vào layout --}}
    <div class="container">

    {{-- Banner đầu trang --}}
    <div class="banner-wrapper">
        <img src="{{ asset('images/banner1.png') }}" alt="Banner" class="banner-img">
    </div>

        {{-- Banner đầu trang --}}
        <div class="banner-wrapper">
            @foreach ($slides as $slide)
                <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->name }}" class="banner-img">
            @endforeach
        </div>

        {{-- Khu vực sản phẩm nổi bật --}}
        <section class="featured-products">
            <h2>SẢN PHẨM NỔI BẬT</h2>

            <div class="product-grid">
                {{-- Hiển thị sản phẩm nổi bật --}}
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                        <div class="product-image">
                            {{-- Ảnh sản phẩm --}}
                            <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                        </div>

                        <div class="product-info">
                            {{-- Tên sản phẩm --}}
                            <h3>{{ $product->product_name }}</h3>

                            {{-- Giá sản phẩm --}}
                            <p class="price">{{ number_format($product->price) }}₫</p>

                            {{-- Mô tả ngắn --}}
                            <p class="description">{{ $product->description }}</p>

                            {{-- Nút yêu thích --}}
                            <button class="like-btn">❤</button>

                            {{-- Nút Thêm vào giỏ --}}
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
        </section>
        <!-- Nút trở về đầu trang -->
    <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
    </div>
@endsection
{{-- Kết thúc section content --}}

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

