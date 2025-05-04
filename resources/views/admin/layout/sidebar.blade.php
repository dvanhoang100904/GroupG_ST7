<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <span>Quản lý</span>
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/home.png') }}" class="icon" />
                <p>Dashboard</p>
            </a>
        </li>
        <li>
            <a href="#">
                <img src="{{ asset('images/users.png') }}" class="icon" />
                <p>Người dùng</p>
            </a>
        </li>
        <li>
            <a href="{{ route('products.list') }}">
                <img src="{{ asset('images/products.png') }}" class="icon" />
                <p>Sản phẩm</p>
            </a>
        </li>
        <li>
            <a href="{{ route('category.index') }}">
                <img src="{{ asset('images/category.png') }}" class="icon" />
                <p>Danh mục</p>
            </a>
        </li>
        <li>
            <a href="{{ route('order.list') }}">
                <img src="{{ asset('images/order.png') }}" class="icon" />
                <p>Đơn hàng</p>
            </a>
        </li>
        <li>
            <a href="#">
                <img src="{{ asset('images/website.png') }}" class="icon" />
                <p>Website</p>
            </a>
        </li>
        <li>
            <a href="{{ route('slide.index') }}">
                <img src="{{ asset('images/banner.png') }}" class="icon" />
                <p>Slide</p>
            </a>
        </li>
        <span>Chức năng</span>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <img src="{{ asset('images/logout.png') }}" class="icon" />
                <p>Thoát</p>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
