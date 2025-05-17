@extends('admin.layout.app') {{-- Kế thừa layout chung admin --}}

@section('page_title', 'Thêm mới sản phẩm') {{-- Đặt tiêu đề trang --}}

@section('content')
<div class="container py-3">

    {{-- Hiển thị thông báo thành công nếu có --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Hiển thị danh sách lỗi nếu có --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> {{-- Hiển thị từng lỗi --}}
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Nút quay lại danh sách sản phẩm --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('products.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    {{-- Form gửi dữ liệu lên route products.store --}}
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf {{-- Token chống CSRF --}}

        {{-- Tên sản phẩm --}}
        <div class="mb-3">
            <label for="product_name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="product_name" id="product_name"
                   class="form-control @error('product_name') is-invalid @enderror"
                   value="{{ old('product_name') }}" required>
            {{-- Hiển thị lỗi nếu có --}}
            @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Slug sản phẩm --}}
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug"
                   class="form-control @error('slug') is-invalid @enderror"
                   value="{{ old('slug') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả sản phẩm --}}
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả sản phẩm</label>
            <textarea name="description" id="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Giá sản phẩm --}}
        <div class="mb-3">
            <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price"
                   class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price') }}" step="0.01" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Upload ảnh --}}
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="image" id="image"
                   class="form-control @error('image') is-invalid @enderror">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Chọn danh mục --}}
        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id"
                    class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nút submit --}}
        <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
    </form>
</div>
@endsection
