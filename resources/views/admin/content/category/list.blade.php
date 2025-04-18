@extends('admin.layout.app')

@section('title', 'Quản lý danh mục')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Danh sách danh mục</h3>
            <a href="{{ route('admin.category.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
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
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('admin.category.read', $category->category_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            <a href="{{ route('admin.category.edit', $category->category_id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.category.destroy', $category->category_id) }}" method="POST"
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
