@extends('customer.layouts.app')
@section('title', 'Đăng nhập')
@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header text-center">
                <div class="logo mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="140">
                </div>
                <h2 class="mb-3">Đăng Nhập</h2>
            </div>

            <form action="{{ route('customer.authLogin') }}" method="POST" class="login-form">
                @csrf
                <!-- Email Field -->
                <div class="form-group mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" value="{{ old('email') }}" id="email" name="email"
                            placeholder="Nhập email của bạn" required>
                    </div>
                    @if ($errors->has('email'))
                        <span class="error-message"><i
                                class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <!-- Password Field -->
                <div class="form-group mb-4">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Nhập mật khẩu" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <span class="error-message"><i
                                class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password text-dark text-decoration-none">
    Quên mật khẩu?
</a>


                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-light btn-login w-100 mb-3 py-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                </button>

                <div class="divider my-4">
                    <span class="divider-line"></span>
                    <span class="divider-text">Hoặc tiếp tục với</span>
                    <span class="divider-line"></span>
                </div>

                            <div class="social-login mb-4">
                <a href="/auth/facebook" class="btn btn-outline-primary btn-social d-block mb-2">
                    <i class="fab fa-facebook-f me-2"></i> Facebook
                </a>
                <a href="/auth/google" class="btn btn-outline-danger btn-social d-block">
                    <i class="fab fa-google me-2"></i> Google
                </a>
            </div>


                <div class="text-center register-link pt-3">
                    <a href="#!" class="text-dark text-decoration-none fw-bold"> Đăng ký</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Password visibility toggle
        document.getElementById("togglePassword").addEventListener("click", function() {
            const passwordInput = document.getElementById("password");
            const icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });

        // Form validation
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;

            if (!email.value) {
                email.classList.add('is-invalid');
                isValid = false;
            }
            if (!password.value) {
                password.classList.add('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Clear validation on input
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });

        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    </script>
@endpush
