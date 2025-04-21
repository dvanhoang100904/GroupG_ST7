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
                @foreach($categories as $category)
                    <a href="{{ route('category.show', ['slug' => $category->slug ?? Str::slug($category->category_name)]) }}">
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

        {{-- Link đến tài khoản người dùng --}}
        <a href="#"><i class="fa fa-user"></i> Tài khoản</a>

        {{-- Link đến giỏ hàng --}}
        <a href="#"><i class="fa fa-shopping-cart"></i> Giỏ hàng</a>
    </div>

</div>
