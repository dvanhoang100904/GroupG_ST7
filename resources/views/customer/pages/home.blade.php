@extends('customer.layouts.app')
{{-- Kế thừa layout giao diện chung --}}

@section('content')
    {{-- Bắt đầu nội dung chính sẽ được chèn vào layout --}}
    <div class="container">

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- Banner đầu trang 
        <div class="banner-wrapper">
            @foreach ($slides as $slide)
                <img src="{{ asset($slide->image) }}" alt="{{ $slide->name }}" class="banner-img">
            @endforeach
        </div> --}}
        <div class="banner-wrapper container-fluid px-0">
            <div class="row g-0">
                @foreach ($slides as $slide)
                    <div class="col-12">
                        <div class="banner-item">
                            <img src="{{ asset($slide->image) }}" alt="{{ $slide->name }}">
                        </div>
                    </div>
                @endforeach
            </div>
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
                            @php
                                $imagePath = public_path($product->image);
                                $categorySlug = \Illuminate\Support\Str::slug(optional($product->category)->category_name ?? 'mac-dinh');
                                $defaultImage = "images/{$categorySlug}/mac-dinh.jpg";
                            @endphp

                            @if ($product->image && file_exists($imagePath))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                            @elseif (file_exists(public_path($defaultImage)))
                                <img src="{{ asset($defaultImage) }}" alt="Ảnh mặc định">
                            @else
                                <p class="text-muted">Không có ảnh</p>
                            @endif

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
    <div id="open-chat-button"
        style="position: fixed; bottom: 0; right: 0; background: #e40000; color: white; padding: 10px; border-radius: 12px 0 0 0; cursor: pointer; font-family: Arial; z-index: 9999;"
        onclick="toggleChatBox(true)">
        💬 Chat với nhân viên tư vấn
    </div>

    <!-- Chat box -->
    <div id="chat-box"
        style="display: none; position: fixed; bottom: 0; right: 0; width: 360px; height: 600px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); z-index: 10000; font-family: Arial, sans-serif; display: flex; flex-direction: column; background: white;">

        <!-- Header -->
        <div style="background-color: #e40000; color: white; padding: 10px; display: flex; align-items: center;">
            <img src="https://i.imgur.com/N5uCbDu.png" alt="avatar"
                style="width: 32px; height: 32px; border-radius: 50%; margin-right: 10px;">
            <div style="flex-grow: 1;">
                <strong>Hỗ trợ khách hàng</strong><br>
                <span style="font-size: 12px;">Chat trực tiếp tại Website</span>
            </div>
            <div style="cursor: pointer;" onclick="toggleChatBox(false)">❌</div>
        </div>

        @auth
            @if (Auth::user()->role_id === 2)
                <!-- Nội dung chat -->
                <div style="flex: 1; display: flex; flex-direction: column;">

                    <!-- Tin nhắn -->
                    <div class="chat-messages" id="chatMessages"
                        style="flex: none; height: 300px; padding: 10px; overflow-y: auto; background: #f7f7f7;">
                        @foreach ($chats as $chat)
                            @if ($chat->user_id === auth()->id())
                                <div style="text-align: right; margin-bottom: 8px;">
                                    <div
                                        style="display: inline-block; background-color: #006AFF; color: white; padding: 8px 12px; border-radius: 18px 18px 0 18px; max-width: 75%;">
                                        {{ $chat->description }}
                                        <div style="font-size: 10px; color: #d9d9d9; text-align: right;">
                                            {{ $chat->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="text-align: left; margin-bottom: 8px;">
                                    <div
                                        style="display: inline-block; background-color: #e4e6eb; color: black; padding: 8px 12px; border-radius: 18px 18px 18px 0; max-width: 30 %;">
                                        {{ $chat->description }}
                                        <div style="font-size: 10px; color: #888; text-align: right;">
                                            {{ $chat->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Form cố định -->
                    <form action="{{ route('customer.chats.store') }}" method="POST" enctype="multipart/form-data"
                        style="border-top: 1px solid #ccc; background: white;">
                        @csrf
                        <div style="display: flex; align-items: center; padding: 2px 5px; gap: 5px;">
                            <label for="chatFile" style="cursor: pointer; font-size: 16px;">
                                📎
                            </label>
                            <input id="chatFile" name="file" type="file" accept="image/*" style="display: none;">
                            <input name="message" id="chatInput" type="text" placeholder="Nhập nội dung..." required
                                style="flex: 1; border: none; outline: none; padding: 4px 6px; font-size: 12px;">
                            <button type="submit"
                                style="background: none; border: none; color: #e40000; font-size: 16px;">📤</button>
                        </div>
                    </form>
                </div>
            @endif
        @endauth

        @guest
            <div class="text-center p-3">
                <p>Vui lòng <a href="{{ route('customer.login') }}">đăng nhập</a> để sử dụng chức năng chat.</p>
            </div>
        @endguest
    </div>

    <!-- Script toggle + scroll -->
    <script>
        function toggleChatBox(show) {
            const chatBox = document.getElementById("chat-box");
            const openButton = document.getElementById("open-chat-button");
            if (show) {
                chatBox.style.display = "flex";
                openButton.style.display = "none";

                // Scroll xuống cuối
                setTimeout(() => {
                    const chatMessages = document.getElementById("chatMessages");
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                }, 100);
            } else {
                chatBox.style.display = "none";
                openButton.style.display = "block";
            }
        }
    </script>

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