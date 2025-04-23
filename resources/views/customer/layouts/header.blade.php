<div class="main-header">
    <!-- Trái: Logo -->
    <div class="header-left">
        {{-- Logo dẫn về trang chủ --}}
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
        </a>
    </div>

    <!-- Giữa: Menu điều hướng -->
    <div class="header-center">
        {{-- Link trang chủ --}}
        <a href="{{ route('home') }}">Trang chủ</a>

        {{-- Dropdown danh mục --}}
        <div class="dropdown">
            <div class="dropdown-toggle">Danh mục</div>
            <div class="dropdown-content">
                @php use Illuminate\Support\Str; @endphp

                {{-- Danh sách các danh mục từ CSDL --}}
                @foreach ($categories as $category)
                    <a
                        href="{{ route('category.show', ['slug' => $category->slug ?? Str::slug($category->category_name)]) }}">
                        {{ $category->category_name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Link đến trang tất cả sản phẩm --}}
        <a href="{{ route('products.index') }}">Sản phẩm</a>

        {{-- Link tùy chọn (chưa xử lý) --}}
        <a href="#">Sản phẩm theo sở thích</a>
    </div>

    <!-- Phải: Tìm kiếm + Tài khoản + Giỏ hàng -->
    <div class="header-right">
        {{-- Ô tìm kiếm sản phẩm --}}
        <div class="search-container">
            <input type="text" class="search-box" placeholder="Tìm kiếm...">
            <button class="search-btn"><i class="fa fa-search"></i></button>
        </div>
        {{-- Link đến giỏ hàng --}}
        <a href="{{ route('cart.list') }}" class="position-relative text-decoration-none text-light">
            <i class="fa fa-shopping-cart fs-5"></i> <span class="ms-1">Giỏ hàng</span>
            @if (Auth::check() && Auth::user()->cart)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                    {{ Auth::user()->cart->cartItems->count() }}
                </span>
            @endif
        </a>

    </div>

    <div class="d-flex align-items-center">
        @guest
            <a href="{{ route('customer.login') }}" class="btn btn-outline-light me-2">
                Đăng nhập
            </a>
            <a href="{{ route('customer.register') }}" class="btn btn-light">Đăng ký</a>
            </a>
        @endguest

        @auth
            @if (Auth::user()->role_id === 2)
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar ?? 'avatar.png' }}" alt="Avatar" class="rounded-circle me-2"
                            width="32" height="32">
                        <span class="text-light">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i> Hồ sơ
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('customer.logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        @endauth
    </div>
</div>
