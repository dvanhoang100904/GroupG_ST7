@extends('customer.layouts.app')
{{-- Kế thừa layout chung từ layouts/app.blade.php --}}
@section('content')
{{-- Bắt đầu phần nội dung sẽ được render vào @yield('content') trong layout --}}

<div class="container">
    {{-- Tiêu đề cho trang sản phẩm --}}
    <h2 class="product-heading">SẢN PHẨM</h2>

    <div class="product-grid">
        {{-- Lặp qua danh sách sản phẩm --}}
        @foreach($products as $product)
            {{-- Liên kết đến trang chi tiết sản phẩm dựa trên slug --}}
            <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                <div class="product-image">
                    {{-- Ảnh sản phẩm từ đường dẫn trong DB --}}
                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                </div>

                <div class="product-info">
                    {{-- Tên sản phẩm --}}
                    <h3>{{ $product->product_name }}</h3>

                    {{-- Giá sản phẩm, định dạng với dấu phân cách hàng nghìn --}}
                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                    {{-- Mô tả sản phẩm, chỉ hiển thị 100 ký tự đầu --}}
                    <p class="description">{{ Str::limit($product->description, 100) }}</p>

                    {{-- Nút yêu thích (hiện tại không có hành động gì khi nhấn) --}}
                    <button class="like-btn" onclick="event.preventDefault()">❤</button>

                    {{-- Nút Thêm vào giỏ --}}
                    <form method="POST" action="#" onClick="event.stopPropagation()">
                        @csrf
                        <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                    </form>
                </div>
            </a>
        @endforeach
    </div>
</div>

@endsection
{{-- Kết thúc section content --}}
