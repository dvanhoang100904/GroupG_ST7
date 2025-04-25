@extends('admin.layout.app')

@section('page_title', 'Chỉnh sửa Slide')

@section('content')
    <div class="container py-3">
        <form action="{{ route('slide.update', $slide->slide_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tên slide --}}
            <div class="mb-3">
                <label for="name" class="form-label">Tên slide</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $slide->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ảnh hiện tại --}}
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh hiện tại</label>
                <div class="mb-2">
                    @if ($slide->image)
                        <img id="current-image" src="{{ asset('storage/' . $slide->image) }}" alt="Current Image" width="150">
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
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror"
                       accept="image/*" onchange="previewImage(event)">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nút hành động --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('slide.index') }}" class="btn btn-secondary">Quay lại</a>
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
