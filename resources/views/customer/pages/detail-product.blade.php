@extends('customer.layouts.app')
@section('title', $product->product_name)

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

                {{-- Thêm sản phẩm vào giỏ hàng --}}
                <form action="{{ route('cart.addToCart') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}" />

                    <div class="product-actions">
                        <div class="quantity-box">
                            {{-- cộng --}}
                            <button type="button" onclick="changeQuantity(-1)">-</button>

                            {{-- số lượng --}}
                            <input type="number" name="quantity" id="product-quantity-input" value="1" min="1" required>

                            {{-- trừ --}}
                            <button type="button" onclick="changeQuantity(1)">+</button>
                        </div>
                        {{-- action --}}
                        <button type="submit" class="add-to-cart">Thêm vào giỏ hàng</button>
                    </div>
                </form>

                <button class="buy-now">Mua ngay</button>
                @if(Auth::check())
                    <button class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        Viết đánh giá
                    </button>
                @endif
            </div>
            <!-- Modal đánh giá -->
            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewModalLabel">Đánh giá sản phẩm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Chèn form đánh giá vào modal -->
                            @include('customer.pages.addreview')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- KHU VỰC HIỂN THỊ ĐÁNH GIÁ --}}
        <section class="product-reviews mt-5">
            <h3>Đánh giá sản phẩm</h3>

            @if($reviews->count())
                @foreach($reviews as $review)
                    <div class="review-card mb-3 border p-3 rounded shadow-sm">
                        <strong>{{ $review->user->user_name ?? 'Ẩn danh' }}</strong>
                        <div>⭐ {{ $review->rating }} / 5</div>
                        <p>{{ $review->content }}</p>
                        @if($review->photo)
                            <div>
                                <img src="{{ asset($review->photo) }}" alt="Ảnh đánh giá" style="max-width: 150px;">
                            </div>
                        @endif
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>

                        {{-- Nếu người dùng hiện tại là người viết đánh giá --}}
                        @if(Auth::check() && Auth::id() === $review->user_id)
                            <div class="mt-2 d-flex gap-2">
                                {{-- Nút sửa --}}
                                {{-- <a href="{{ route('reviews.edit', $review->review_id) }}" class="btn btn-sm btn-warning">
                                    Sửa
                                </a> --}}

                                {{-- Nút xóa --}}
                                {{-- <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form> --}}
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
            @endif
        </section>


    </div>

    {{-- Khu vực sản phẩm tương tự --}}
    <section class="related-products">
        <h2>SẢN PHẨM TƯƠNG TỰ</h2>

        <div class="product-grid">
            {{-- Hiển thị sản phẩm tương tự --}}
            @foreach ($similarProducts as $product)
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
                        <form method="POST" action="{{ route('cart.addToCart') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}" />
                            <input type="hidden" name="quantity" id="product-quantity-input" value="1" />
                            <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    </div>
@endsection
@push('scripts')
    <script>
        function changeQuantity(amount) {
            const input = document.getElementById('product-quantity-input');
            let value = parseInt(input.value) || 1;
            value += amount;
            if (value < 1) value = 1;
            input.value = value;
        }
    </script>
@endpush