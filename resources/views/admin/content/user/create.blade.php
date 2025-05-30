@extends('admin.layout.app')

@section('page_title', 'Tạo người dùng mới')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('users.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" id="phone"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Role ID</label>
            <input type="number" name="role_id" id="role_id"
                class="form-control @error('role_id') is-invalid @enderror"
                value="{{ old('role_id') }}" required>
            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện</label>
            <input type="file" name="avatar" id="avatar"
                class="form-control @error('avatar') is-invalid @enderror"
                accept="image/*">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Lưu người dùng
        </button>
    </form>
</div>
@endsection
