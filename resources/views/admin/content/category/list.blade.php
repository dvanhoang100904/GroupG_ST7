@extends('admin.layout.app')

@section('page_title', 'Quản Lý Danh Mục')

@section('content')
    <div class="container py-3">  
        {{-- thong bao --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Thành công!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Lỗi!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('category.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
          

                <!-- Ô tìm kiếm -->
        <div class="search-box">
            <form method="GET" action="{{ route('category.index') }}" class="search-form">
                <input type="text" name="search" placeholder="Tìm kiếm danh mục..." value="{{ request('search') }}">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Mô tả</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->category_id }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('category.read', $category->category_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            <a href="{{ route('category.edit', $category->category_id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('category.destroy', $category->category_id) }}" method="POST"
                                class="d-inline-block"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có danh mục nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Phân trang -->
        @include('admin.layout.pagination', ['paginator' => $categories])
    </div>
@endsection
