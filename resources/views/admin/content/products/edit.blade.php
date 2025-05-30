@extends('admin.layout.app')
{{-- Kế thừa giao diện layout chung của trang admin --}}

@section('page_title', 'Sửa Sản Phẩm')
{{-- Đặt tiêu đề trang là "Sửa Sản Phẩm" --}}

@section('content')
<div class="container py-3">

    {{-- Hiển thị lỗi validation tổng quát --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Đã xảy ra lỗi, vui lòng kiểm tra lại:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form cập nhật sản phẩm --}}
    <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- Sử dụng phương thức PUT để cập nhật dữ liệu (RESTful) --}}

        {{-- Nút quay lại trang danh sách sản phẩm --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('products.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
        
        {{-- Tên sản phẩm --}}
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="product_name"
                class="form-control @error('product_name') is-invalid @enderror"
                value="{{ old('product_name', $product->product_name) }}" required>
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
            @php
                $imagePath = public_path($product->image);
                $categorySlug = \Illuminate\Support\Str::slug(optional($product->category)->category_name ?? 'mac-dinh');
                $defaultImage = "images/{$categorySlug}/mac-dinh.jpg";
            @endphp

            @if ($product->image && file_exists($imagePath))
                <img src="{{ asset($product->image) }}" width="150" alt="{{ $product->product_name }}">
            @elseif (file_exists(public_path($defaultImage)))
                <img src="{{ asset($defaultImage) }}" width="150" alt="Ảnh mặc định">
            @else
                <p class="text-muted">Chưa có ảnh</p>
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

        {{-- Trường version để xử lý cập nhật trùng --}}
        <input type="hidden" name="version" value="{{ $product->version }}">

        {{-- Nút submit --}}
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

{{-- Hiển thị alert nếu có lỗi version_conflict và redirect về list --}}
@if(session('version_conflict'))
<script>
    alert(@json(session('version_conflict')));
    window.location.href = "{{ route('products.list') }}";
</script>
@endif

@endsection

@if(session('not_found'))
<script>
    alert(@json(session('not_found')));
    window.location.href = "{{ route('products.list') }}";
</script>
@endif
