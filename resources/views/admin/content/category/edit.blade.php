@extends('admin.layout.app')

@section('page_title', 'Sửa Danh Mục')

@section('content')
    <div class="container py-3">

        <form action="{{ route('category.update', $category->category_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        
            <div class="mb-3">
                <label for="category_name" class="form-label">Tên danh mục</label>
                <input type="text" name="category_name" class="form-control" value="{{ old('category_name', $category->category_name) }}" required>
            </div>
        
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
            </div>
        
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
            </div>
        
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh hiện tại</label>
        
                {{-- Ảnh hiện tại (nếu có) --}}
                <div class="mb-2">
                    @if ($category->image)
                        <img id="current-image" src="{{ asset('storage/' . $category->image) }}" alt="Current Image" width="150">
                    @else
                        <p>Chưa có ảnh</p>
                    @endif
                </div>
        
                {{-- Ảnh preview sau khi chọn file --}}
                <div class="mb-2" id="preview-container" style="display: none;">
                    <p>Ảnh mới (xem trước):</p>
                    <img id="preview-image" src="" alt="Preview Image" width="150">
                </div>
        
                {{-- Input ảnh --}}
                <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            </div>
        
            <div class="d-flex justify-content-between">
                <a href="{{ route('category.read', $category->category_id) }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>        
    </div>
@endsection
@section('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = '';
                previewContainer.style.display = 'none';
            }
        }
    </script>
@endsection
