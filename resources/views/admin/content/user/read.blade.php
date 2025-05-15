@extends('admin.layout.app')

@section('page_title', 'Chi tiết người dùng')

@section('content')
    <div class="container py-3">
        <div class="mb-4">
            <a href="{{ route('users.list') }}" class="btn btn-secondary">Quay lại</a>
            <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-warning">Sửa</a>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h4>Thông tin chi tiết người dùng</h4>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $user->user_id }}</p>
                <p><strong>Tên:</strong> {{ e($user->name) }}</p>
                <p><strong>Email:</strong> {{ e($user->email) }}</p>
                <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                <p><strong>Role ID:</strong> {{ $user->role_id }}</p>

                <p><strong>Ảnh đại diện:</strong></p>
                @if ($user->avatar)
                    <img src="{{ asset($user->avatar) }}" width="120" alt="Avatar của {{ $user->name }}" class="rounded-circle">
                @else
                    <p>Không có avatar</p>
                @endif
            </div>
        </div>
    </div>
@endsection
