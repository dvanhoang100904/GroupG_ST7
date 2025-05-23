@extends('customer.layouts.app')
@section('title', 'Đăng ký')
@section('content')
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Đăng ký</h2>
            <form action="{{ route('customer.register.submit') }}"method="POST">
                @csrf

                {{-- name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" value="{{ old('name') }}" id="name"
                            name="name" placeholder="Nhập họ và tên" required>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                {{-- email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" value="{{ old('email') }}" id="email"
                            name="email" placeholder="Nhập email của bạn" required>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                {{-- password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Nhập mật khẩu" required>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- confirm password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                {{-- action --}}
                <button type="submit" class="btn btn-success btn-login w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i> Đăng ký
                </button>

                <div class="divider">
                    <span>HOẶC</span>
                </div>

                <div class="social-login mb-4">
                    <button type="button" class="btn btn-outline-primary">
                        <i class="fab fa-facebook-f me-2"></i> Đăng ký với Facebook
                    </button>
                    <button type="button" class="btn btn-outline-danger">
                        <i class="fab fa-google me-2"></i> Đăng ký với Google
                    </button>
                </div>

                <div class="text-center">
                    Đã có tài khoản? <a href="{{ route('customer.login') }}" class="text-decoration-none">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Hiển thị/ẩn mật khẩu
        document.getElementById("togglePassword").addEventListener("click", function () {
            const passwordInput = document.getElementById("password");
            const icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    </script>
@endpush
