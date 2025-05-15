@extends('customer.layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header text-center">
                <div class="logo mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="140">
                </div>
                <h3 class="mb-3">Đặt lại mật khẩu</h3>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="login-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Nhập email của bạn"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Nhập mật khẩu mới"
                            required
                            autocomplete="new-password"
                        >
                    </div>
                    @error('password')
                        <span class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Nhập lại mật khẩu mới"
                            required
                            autocomplete="new-password"
                        >
                    </div>
                    @error('password_confirmation')
                        <span class="error-message"><i class="fas fa-exclamation-circle me-2"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-login w-100 py-3">
                        <i class="fas fa-redo me-2"></i> Đặt lại mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
