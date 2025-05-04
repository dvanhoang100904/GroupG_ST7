@extends('admin.layout.app')

@section('page_title', 'Chi Tiết Sản Phẩm')

@section('content')
    <div class="container py-3">
        <div class="mb-4">
            <a href="{{ route('products.list') }}" class="btn btn-secondary">Quay lại</a>
            <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning">Sửa</a>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h4>Thông tin chi tiết sản phẩm</h4>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $product->product_id }}</p>
                <p><strong>Tên:</strong> {{ $product->product_name }}</p>
                <p><strong>Giá:</strong> {{ $product->price }}</p>
                <p><strong>Mô tả:</strong> {{ $product->description ?: 'Không có mô tả' }}</p>
                <p><strong>Danh mục:</strong> {{ $product->category->category_name ?? 'Không xác định' }}</p>

                @if ($product->image)
                    <p><strong>Ảnh:</strong></p>
                    <img src="{{ asset('storage/' . $product->image) }}" width="200">
                @else
                    <p><strong>Ảnh:</strong> Không có ảnh</p>
                @endif
            </div>
        </div>
    </div>
@endsection
