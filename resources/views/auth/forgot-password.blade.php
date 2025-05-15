@extends('customer.layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header text-center">
                <div class="logo mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="140">
                </div>
                <h3 class="mb-3">Quên mật khẩu?</h3>
                <p class="text-secondary mb-4">
                    Nhập email của bạn, chúng tôi sẽ gửi link để bạn đặt lại mật khẩu mới.
                </p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="login-form">
                @csrf

                <div class="form-group mb-4">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
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

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-login w-100 py-3">
                        <i class="fas fa-envelope-open-text me-2"></i> Gửi link đặt lại mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
