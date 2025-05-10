@extends('customer.layouts.app')

@section('title', $category->category_name)

@section('content')
    <div class="container">
        {{-- Hiển thị tên danh mục sản phẩm --}}
        <h2 class="product-heading">{{ mb_strtoupper($category->category_name) }}</h2>

    {{-- Bộ lọc sắp xếp --}}
    <div class="sort-filter mb-3">
        <label class="sort-label" for="sortForm">Sắp xếp:</label>
        
        <form method="GET"
            id="sortForm"
            @if(isset($category))
                action="{{ route('category.show', $category->slug) }}"
            @else
                action="{{ route('products.index') }}"
            @endif
        >
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
