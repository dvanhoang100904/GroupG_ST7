@extends('admin.layout.app')

@section('page_title', 'Chỉnh sửa người dùng')

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

    <form action="{{ route('users.update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" name="name" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ $errors->any() ? old('name') : $user->name }}" required>

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ $errors->any() ? old('email') : $user->email }}" required>

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" id="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ $errors->any() ? old('phone') : $user->phone }}">

            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Role ID</label>
            <input type="number" name="role_id" id="role_id"
            class="form-control @error('role_id') is-invalid @enderror"
            value="{{ $errors->any() ? old('role_id') : $user->role_id }}" required>

            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu (để trống nếu không đổi)</label>
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Avatar hiện tại</label><br>
            @if($user->avatar)
                <img src="{{ asset($user->avatar) }}" width="100" alt="Avatar người dùng" class="img-thumbnail">
            @else
                <p class="text-muted">Chưa có avatar</p>
            @endif
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Thay đổi Avatar</label>
            <input type="file" name="avatar" id="avatar"
                   class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <input type="hidden" name="updated_at" value="{{ $user->updated_at }}">

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Cập nhật người dùng
        </button>
    </form>
</div>
@endsection
