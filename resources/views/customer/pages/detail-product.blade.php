@extends('customer.layouts.app')

@section('content')
<div class="container product-detail-page">
    {{-- Ảnh và thông tin sản phẩm --}}
    <div class="product-top-section">
        {{-- Hình ảnh --}}
        <div class="product-image">
            <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
        </div>

        {{-- Thông tin sản phẩm --}}
        <div class="product-info">
            <h2>{{ $product->product_name }}</h2>
            <p class="category">{{ $product->category->category_name ?? 'Chưa phân loại' }}</p>
            <p class="price">{{ number_format($product->price, 0, ',', '.') }} VND</p>
            <p class="description">{{ $product->description }}</p>

            <div class="product-actions">
                <div class="quantity-box">
                    <button type="button">-</button>
                    <input type="number" value="1" min="1">
                    <button type="button">+</button>
                </div>

                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="add-to-cart">Thêm vào giỏ hàng</button>
                </form>
            </div>

            <button class="buy-now">Mua ngay</button>
        </div>
    </div>

    {{-- Khu vực sản phẩm tương tự --}}
    <section class="related-products">
        <h2>SẢN PHẨM TƯƠNG TỰ</h2>

        <div class="product-grid">
            {{-- Hiển thị sản phẩm tương tự --}}
            @foreach($similarProducts as $product)
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

                        {{-- Mô tả ngắn --}}
                        <p class="description">{{ $product->description }}</p>

                        {{-- Nút yêu thích --}}
                        <button class="like-btn">❤</button>

                        {{-- Nút Thêm vào giỏ --}}
                        <form method="POST" action="#">
                            @csrf
                            <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection