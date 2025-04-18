@extends('admin.layout.app')

@section('title', 'Thêm danh mục')
@section('page_title', 'Thêm mới danh mục')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Thêm mới danh mục</h3>
            <a href="{{ route('category.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                    value="{{ old('slug') }}">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                    rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ảnh danh mục</label>
                <input type="file" name="image" id="image"
                    class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Lưu danh mục</button>
        </form>
    </div>
@endsection
