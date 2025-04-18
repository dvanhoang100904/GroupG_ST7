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
            <a href="#">
                <img src="{{ asset('images/products.png') }}" class="icon" />
                <p>Sản phẩm</p>
            </a>
        </li>
        <li>
            <a href="#">
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
            <a href="#">
                <img src="{{ asset('images/banner.png') }}" class="icon" />
                <p>Banner</p>
            </a>
        </li>
    </ul>
    <ul>
        <span>Chức năng</span>
        <li>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: flex; align-items: center;">
                @csrf
                <button type="submit"
                    style="border: none; background: none; padding: 0; display: flex; align-items: center; cursor: pointer;">
                    <img src="{{ asset('images/logout.png') }}" class="icon" />
                    <p style="margin: 0 0 0 5px;">Thoát</p>
                </button>
            </form>
        </li>
    </ul>
</div>
