@extends('admin.layout.app')

@section('page_title', 'Danh sách người dùng')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Quản lý người dùng</h4>
        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Thêm người dùng mới
        </a>
    </div>

    <!-- Search -->
    <form method="GET" action="{{ route('users.list') }}" class="input-group mb-3">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
               placeholder="Tìm kiếm tên hoặc email...">
        <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i> Tìm
        </button>
    </form>

    <!-- Alert -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Role</th>
                    <th>Avatar</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        <td>{{ $user->role_id }}</td>
                        <td>
                            @if ($user->avatar)
                                <img src="{{ asset($user->avatar) }}" width="50" height="50" class="rounded-circle" alt="Avatar">
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.read', $user->user_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa người dùng này?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có người dùng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
         <!-- Phân trang -->
        {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
