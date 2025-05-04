@extends('admin.layout.app')

@section('page_title', 'Sửa Sản Phẩm')

@section('content')
    <div class="container py-3">
        <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh hiện tại</label><br>
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" width="150">
                @else
                    <p>Chưa có ảnh</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Cập nhật ảnh mới</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection
