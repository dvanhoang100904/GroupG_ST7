<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('customer.index') }}">
                <img src="/logo.png" alt="Logo" height="32">
            </a>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <div class="d-flex align-items-center">
                    @guest
                        <a href="{{ route('customer.login') }}" class="btn btn-outline-success me-2">
                            Đăng nhập
                        </a>
                        <a class="btn btn-primary" data-bs-toggle="modal"> Đăng ký
                        </a>
                    @endguest

                    @auth
                        @if (Auth::user()->role_id === 2)
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ Auth::user()->avatar ?? 'avatar.png' }}" alt="Avatar"
                                        class="rounded-circle me-2" width="32" height="32">
                                    <span>{{ Auth::user()->name }}</span>
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
        </div>
    </nav>
</header>
