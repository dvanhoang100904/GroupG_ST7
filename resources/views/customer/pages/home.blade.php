@extends('customer.layouts.app')
{{-- Kế thừa layout giao diện chung --}}

@section('content')
    {{-- Bắt đầu nội dung chính sẽ được chèn vào layout --}}
    <div class="container">

        {{-- Banner đầu trang --}}
        <div class="banner-wrapper">
            @foreach ($slides as $slide)
                <img src="{{ asset($slide->image) }}" alt="{{ $slide->name }}" class="banner-img">
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
 <!-- Nút mở chat -->
<div id="chat-toggle" style="position: fixed; bottom: 90px; right: 30px; z-index: 999;">
    <button class="btn btn-primary rounded-circle" style="width: 60px; height: 60px;" onclick="toggleChat()">
        <i class="far fa-comment-dots"></i>
    </button>
</div>

<!-- Form Chat (ẩn ban đầu) -->
<div id="chat-box" style="display: none; position: fixed; bottom: 90px; right: 20px; width: 320px; z-index: 9999; font-family: Arial, sans-serif;">
    @auth
        @if (Auth::user()->role_id === 2)
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Hỗ trợ khách hàng</span>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="toggleChat()"></button>
                </div>
                <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                    <!-- Danh sách tin nhắn -->
                    <div class="chat-messages" style="max-height: 250px; overflow-y: auto; margin-bottom: 10px;">
                        @foreach ($chats as $chat)
                            @if ($chat->user_id === auth()->id())
                                <!-- Tin nhắn khách hàng -->
                                <div style="text-align: right; margin-bottom: 8px;">
                                    <div style="display: inline-block; background-color: #006AFF; color: white; padding: 8px 12px; border-radius: 18px 18px 0 18px; max-width: 75%;">
                                        {{ $chat->description }}
                                        <div style="font-size: 10px; color: #d9d9d9; text-align: right;">{{ $chat->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @else
                                <!-- Tin nhắn admin -->
                                <div style="text-align: left; margin-bottom: 8px;">
                                    <div style="display: inline-block; background-color: #e4e6eb; color: black; padding: 8px 12px; border-radius: 18px 18px 18px 0; max-width: 75%;">
                                        {{ $chat->description }}
                                        <div style="font-size: 10px; color: #888; text-align: right;">{{ $chat->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Form gửi tin nhắn -->
                    <form action="{{ route('customer.chats.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="message" class="form-control" rows="2" placeholder="Nhập nội dung..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-sm">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Auto scroll -->
            <script>
                const chatMessages = document.querySelector('.chat-messages');
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            </script>
        @endif
    @endauth

    @guest
        <div class="card border-danger shadow">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span>Vui lòng đăng nhập</span>
                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="toggleChat()"></button>
            </div>
            <div class="card-body text-center">
                <p>Vui lòng <a href="{{ route('customer.login') }}">đăng nhập</a> hoặc đăng ký để sử dụng chức năng chat.</p>
            </div>
        </div>
    @endguest
</div>

<!-- Script toggle chat -->
<script>
    function toggleChat() {
        const chatBox = document.getElementById("chat-box");
        if (chatBox.style.display === "none") {
            chatBox.style.display = "block";
        } else {
            chatBox.style.display = "none";
        }
    }
</script>


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

