@extends('admin.layout.app')
{{-- Kế thừa giao diện layout chung của trang admin --}}

@section('page_title', 'Sửa Sản Phẩm')
{{-- Đặt tiêu đề trang là "Sửa Sản Phẩm" --}}

@section('content')
<div class="container py-3">
    
    {{-- Form cập nhật sản phẩm --}}
    <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- Sử dụng phương thức PUT để cập nhật dữ liệu (RESTful) --}}
        
        {{-- Tên sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="product_name"
                class="form-control @error('product_name') is-invalid @enderror"
                value="{{ old('product_name', $product->product_name) }}" required>
            {{-- Hiển thị lỗi nếu có --}}
            @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" 
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Giá sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="text" name="price"
                class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', $product->price) }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Danh mục sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                {{-- Lặp qua danh sách danh mục để tạo option --}}
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}"
                        {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh hiện tại của sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label><br>
            @if ($product->image)
                <img src="{{ asset($product->image) }}" width="150" alt="{{ $product->product_name }}">
            @else
                <p>Chưa có ảnh</p>
            @endif
        </div>

        {{-- Upload ảnh mới --}}
        <div class="mb-3">
            <label class="form-label">Cập nhật ảnh mới</label>
            <input type="file" name="image"
                class="form-control @error('image') is-invalid @enderror"
                accept=".jpg,.jpeg,.png,.gif,image/*">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nút submit --}}
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
