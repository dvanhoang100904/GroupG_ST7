@extends('admin.layout.app')

@section('page_title', 'Chi Tiết Danh Mục')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('category.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về danh sách
            </a>
            <a href="{{ route('category.edit', $category->category_id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Sửa danh mục
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h4>Thông tin chi tiết danh mục</h4>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $category->category_id }}</p>
                <p><strong>Tên danh mục:</strong> {{ $category->name }}</p>
                <p><strong>Slug:</strong> {{ $category->slug }}</p>
                <p><strong>Mô tả:</strong> {{ $category->description ?: 'Không có mô tả' }}</p>

                @if ($category->image)
                    <div>
                        <strong>Hình ảnh:</strong><br>
                        <img src="{{ asset('storage/' . $category->image) }}" alt="Ảnh danh mục" class="img-thumbnail"
                            style="max-width: 300px;">
                    </div>
                @else
                    <p><strong>Hình ảnh:</strong> Không có ảnh</p>
                @endif
            </div>
        </div>
    </div>
@endsection
