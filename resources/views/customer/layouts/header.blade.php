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

                {{-- Lặp qua danh sách các danh mục lấy từ cơ sở dữ liệu --}}
                @foreach ($categoryList as $category)
                    {{-- Link tới trang hiển thị sản phẩm theo danh mục --}}
                    <a href="{{ route('category.show', ['slug' => $category->slug ?? Str::slug($category->category_name)]) }}">
                        {{ $category->category_name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Link đến trang hiển thị tất cả sản phẩm --}}
        <a href="{{ route('products.index') }}">Sản phẩm</a>

        {{-- Link đến trang yêu thích (chưa xử lý chi tiết) --}}
        <a href="{{ route('favorites.index') }}">Yêu thích</a>
    </div>

    <!-- Phải: Tìm kiếm + Giỏ hàng + Thông báo (nếu có) + Tài khoản -->
    <div class="header-right">
        {{-- Form tìm kiếm sản phẩm --}}
        <form action="{{ route('products.index') }}" method="GET" class="search-container">
            <input type="text" name="search" class="search-box" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            <button class="search-btn"><i class="fa fa-search"></i></button>
        </form>

        {{-- Link đến giỏ hàng --}}
        <a href="{{ route('cart.list') }}" class="position-relative text-decoration-none text-light">
            <i class="fa fa-shopping-cart fs-5"></i> <span class="ms-1">Giỏ hàng</span>
            {{-- Hiển thị số lượng sản phẩm trong giỏ hàng nếu người dùng đã đăng nhập và có giỏ --}}
            @if (Auth::check() && Auth::user()->cart)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                    {{ Auth::user()->cart->cartItems->count() }}
                </span>
            @endif
        </a>
    </div>

    {{-- Phần thông báo cho người dùng đã đăng nhập với vai trò khách hàng (role_id = 2) --}}
    @auth
    @if (Auth::user()->role_id === 2)
        <div class="dropdown me-3 position-relative">
            <button class="btn text-light position-relative" id="notificationBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell fs-5"></i>
                {{-- Hiển thị badge số lượng thông báo chưa đọc nếu có --}}
                @if ($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            {{-- Dropdown danh sách thông báo --}}
            <ul class="dropdown-menu dropdown-menu-end p-2 shadow" aria-labelledby="notificationBtn" style="width: 300px;">
                <li class="fw-bold px-2 mb-2">🔔 Thông báo</li>
                <hr class="m-1">

                {{-- Lặp qua các thông báo --}}
                @forelse ($notifications as $notification)
                    <li class="mb-2">
                        {{-- Link đánh dấu thông báo đã đọc và hiển thị tiêu đề, nội dung, thời gian --}}
                        <a href="{{ route('notifications.read', $notification->notification_id) }}"
                            class="dropdown-item {{ $notification->is_read ? 'text-muted' : 'fw-bold' }}"
                            style="white-space: normal; word-wrap: break-word;">
                            <strong>{{ $notification->title }}</strong><br>
                            <small class="d-block text-truncate" style="max-width: 100%; white-space: normal;">
                                {{ \Illuminate\Support\Str::limit($notification->content, 80) }}
                            </small>
                            <small class="d-block text-muted" style="font-size: 0.75rem;">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </small>
                        </a>
                    </li>
                @empty
                    {{-- Nếu không có thông báo nào --}}
                    <li class="dropdown-item text-muted">Không có thông báo nào</li>
                @endforelse
                <li><hr class="dropdown-divider"></li>
            </ul>
        </div>
    @endif
    @endauth

    {{-- Phần hiển thị nút Đăng nhập / Đăng ký hoặc thông tin tài khoản khi đã đăng nhập --}}
    <div class="d-flex align-items-center">
        @guest
            {{-- Nếu chưa đăng nhập, hiện nút Đăng nhập và Đăng ký --}}
            <a href="{{ route('customer.login') }}" class="btn btn-outline-light me-2">
                Đăng nhập
            </a>
            <a href="{{ route('customer.register') }}" class="btn btn-light">Đăng ký</a>
        @endguest

        @auth
            {{-- Nếu đã đăng nhập với vai trò khách hàng --}}
            @if (Auth::user()->role_id === 2)
                <div class="dropdown">
                    {{-- Hiển thị avatar và tên người dùng, dropdown menu --}}
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img 
                            src="{{ asset(Auth::user()->avatar ?: 'images/logo.jpg') }}" 
                            alt="Avatar" 
                            class="rounded-circle me-2" 
                            width="32" 
                            height="32">

                        <span class="text-light">{{ Auth::user()->name }}</span>
                    </a>

                    {{-- Menu dropdown tài khoản --}}
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">
                                <i class="fas fa-user me-2"></i> Hồ sơ
                            </a>
                        </li>

                        {{-- Link lịch sử mua hàng --}}
                        <li>
                            <a class="dropdown-item" href="{{ route('purchase.history') }}">
                                <i class="fas fa-history me-2"></i> Lịch sử mua hàng
                            </a>
                        </li>

                        {{-- Link quản lý sổ địa chỉ --}}
                        <li>
                            <a class="dropdown-item" href="{{ route('shipping_address.index') }}">
                                <i class="fas fa-map-marker-alt me-2"></i> Sổ địa chỉ
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        {{-- Nút đăng xuất --}}
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
