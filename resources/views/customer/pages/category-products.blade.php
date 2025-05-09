@extends('customer.layouts.app')

@section('title', $category->category_name)

@section('content')
    <div class="container">
        {{-- Hiển thị tên danh mục sản phẩm --}}
        <h2 class="product-heading">{{ mb_strtoupper($category->category_name) }}</h2>

        <div class="product-grid">
            {{-- Kiểm tra nếu có sản phẩm trong danh mục --}}
            @forelse($products as $product)
                <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                    <div class="product-image">
                        {{-- Ảnh sản phẩm từ đường dẫn trong cơ sở dữ liệu --}}
                        <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                    </div>

                    <div class="product-info">
                        {{-- Tên sản phẩm --}}
                        <h3>{{ $product->product_name }}</h3>
                        {{-- Giá sản phẩm --}}
                        <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                        {{-- Mô tả ngắn về sản phẩm --}}
                        <p class="description">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>

                        {{-- Nút yêu thích --}}
                        <button class="like-btn">❤</button>

                        {{-- Form để thêm sản phẩm vào giỏ hàng --}}
                        <form method="POST" action="{{ route('cart.addToCart') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}" />
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                </a>
            @empty
                {{-- Nếu không có sản phẩm nào, hiển thị thông báo --}}
                <p>Không có sản phẩm nào trong danh mục này.</p>
            @endforelse
        </div>
        @if ($products instanceof \Illuminate\Pagination\Paginator || $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @include('customer.layouts.pagination', ['paginator' => $products])
        @endif
    </div>
@endsection
