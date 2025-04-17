    @extends('customer.layouts.content')
    @section('title', 'Đăng nhập')
    @section('content')
        <div class="container">
            <div class="login-container">
                <h2 class="text-center mb-4">Đăng nhập</h2>
                <form action="{{ route('customer.authLogin') }}" method="POST">
                    @csrf
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
                            @if ($errors->has('password '))
                                <span class="text-danger">{{ $errors->first('password   ') }}</span>
                            @endif
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="forgot_password.php" class=    "text-decoration-none">Quên mật khẩu?</a>
                    </div>

                    {{-- action --}}
                    <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                    </button>

                    <div class="divider">
                        <span>HOẶC</span>
                    </div>

                    <div class="social-login mb-4">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i> Đăng nhập với Facebook
                        </button>
                        <button type="button" class="btn btn-outline-danger">
                            <i class="fab fa-google me-2"></i> Đăng nhập với Google
                        </button>
                    </div>

                    <div class="text-center">
                        Chưa có tài khoản? <a href="#!" class="text-decoration-none">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    @endsection
