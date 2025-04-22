<!-- Header -->
<div class="navbar">
    <!-- Tiêu đề Dashboard -->
    <div class="navbar-title">
        <h2>@yield('page_title', 'Dashboard')</h2>
    </div>

    <!-- Ô tìm kiếm -->
    <div class="search-box">
        <form method="GET" action="{{ route('category.index') }}" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm danh mục..." value="{{ request('search') }}">
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Logo -->
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" />
    </div>
</div>
