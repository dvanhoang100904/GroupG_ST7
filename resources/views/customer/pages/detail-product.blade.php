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
                <div>
                    <button class="buy-now">Mua ngay</button>
                    <button id="writeReviewBtn" class="btn btn-outline-primary mt-3">
                        Viết đánh giá <i class="fas fa-comment ms-2"></i>
                    </button>
                </div>
            </div>
            <!-- Modal đánh giá -->
            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewModalLabel">
                                <i class="fas fa-comment"></i> Đánh giá sản phẩm
                            </h5>
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

        <section class="product-reviews mt-5">
            <h3 class="mb-4">Đánh giá sản phẩm</h3>

            <!-- Bộ lọc đánh giá -->
            <div class="mb-3">
                <form method="GET">
                    <label for="rating">Lọc theo số sao:</label>
                    <select class="form-control w-25 d-inline-block" name="rating" id="rating"
                        onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ str_repeat('★', $i) . str_repeat('☆', 5 - $i) }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            @if($reviews->count())
                @foreach($reviews as $review)
                    <div class="review-box">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong>
                                <div class="star-rating">
                                    {{ str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>
                            @if(Auth::check() && Auth::id() === $review->user_id)
                                <div class="actions text-right">
                                    <i class="fa fa-pen text-primary" title="Sửa đánh giá" data-bs-toggle="modal"
                                        data-bs-target="#editReviewModal{{ $review->review_id }}"></i>
                                    <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 m-0 text-danger"><i class="fa fa-trash"
                                                title="Xóa đánh giá"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <p class="mt-2 mb-1">{{ $review->content }}</p>

                        @if($review->photo)
                            <img src="{{ asset($review->photo) }}" width="100" class="img-thumbnail">
                        @endif

                        <small class="text-muted d-block mt-2">{{ $review->created_at->format('d/m/Y H:i') }}</small>

                {{-- PHẢN HỒI TẠM THỜI LỒNG TRONG ĐÁNH GIÁ KHÁCH HÀNG --}}
@if (session()->has('temp_replies'))
    @php
        $tempReplies = collect(session('temp_replies'))
            ->where('review_id', $review->review_id)
            ->filter(function($r) {
                return now()->timestamp - $r['time'] <= 900;
            });
    @endphp

    {{-- Chỉ hiển thị nếu review này là của khách hàng (user_id != 1) và có phản hồi tạm --}}
    @if ($review->user_id != 1 && $tempReplies->count())
        @foreach ($tempReplies as $reply)
            <div class="admin-reply mt-3 ms-3 ps-3 border-start border-warning rounded" style="background:#fff9e6;">
                <div class="mb-2 p-2">
                    <strong class="text-warning">Phản hồi từ Admin (tạm thời)</strong>
                    <div class="star-rating text-muted">{!! str_repeat('☆', 5) !!}</div>
                    <p class="mb-1">{{ $reply['content'] }}</p>
                    <small class="text-muted">Gửi lúc {{ \Carbon\Carbon::createFromTimestamp($reply['time'])->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        @endforeach
    @endif
@endif

                    <!-- Modal chỉnh sửa -->
                    <div class="modal fade" id="editReviewModal{{ $review->review_id }}" tabindex="-1"
                        aria-labelledby="editReviewModalLabel{{ $review->review_id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chỉnh sửa Đánh giá</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('reviews.update', $review->review_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá sao</label>
                                            <input type="number" name="rating" class="form-control" value="{{ $review->rating }}"
                                                min="1" max="5" required>
                                        </div>
                                        @if ($review->photo)
                                            <div class="mb-3">
                                                <label class="form-label">Ảnh hiện tại</label><br>
                                                <img src="{{ asset($review->photo) }}" alt="Ảnh đánh giá"
                                                    style="max-width: 100px; border-radius: 8px;">
                                            </div>
                                        @endif

                                        <!-- Chọn ảnh mới -->
                                        <div class="mb-3">
                                            <label class="form-label">Chọn ảnh mới (tùy chọn)</label>
                                            <input type="file" name="photo" accept="image/*" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Nội dung</label>
                                            <textarea name="content" class="form-control" rows="3"
                                                required>{{ $review->content }}</textarea>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
            @endif
        </section>



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
        <!-- Nút trở về đầu trang -->
        <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        document.addEventListener("DOMContentLoaded", function () {
            const isLoggedIn = @json(Auth::check());
            const reviewBtn = document.getElementById("writeReviewBtn");

            reviewBtn.addEventListener("click", function () {
                if (isLoggedIn) {
                    // Nếu đã đăng nhập, mở modal viết đánh giá
                    const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
                    reviewModal.show();
                } else {
                    // Nếu chưa đăng nhập, hiện alert hoặc modal yêu cầu đăng nhập
                    Swal.fire({
                        icon: 'info',
                        title: 'Vui lòng đăng nhập',
                        html: `Bạn cần <a href="{{ route('customer.login') }}">đăng nhập</a> hoặc <a href="http://127.0.0.1:8000/register">đăng ký</a> để viết đánh giá.`,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any() || session('error') || session('success'))
            var reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            reviewModal.show();
        @endif
    });
    </script>
@endpush