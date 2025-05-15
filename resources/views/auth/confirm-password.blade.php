@extends('customer.layouts.app')

@section('title', 'Xác nhận mật khẩu')

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header text-center">
                <div class="logo mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="140">
                </div>
                <h2 class="mb-3">Xác nhận mật khẩu</h2>
                <p class="mb-4 text-muted small">
                    Đây là khu vực bảo mật. Vui lòng xác nhận mật khẩu trước khi tiếp tục.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="login-form">
                @csrf

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password" placeholder="Nhập mật khẩu">
                        <button class="btn btn-outline-secondary toggle-password" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-light btn-login w-100 py-3">
                        <i class="fas fa-check-circle me-2"></i> Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Bật tắt hiển thị mật khẩu
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
</script>
@endpush
