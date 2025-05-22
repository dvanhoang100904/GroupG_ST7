@extends('admin.layout.app')

@section('page_title', 'Chi Tiết Slide')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('slide.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về danh sách
            </a>
            <a href="{{ route('slide.edit', $slide) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Sửa slide
            </a>                      
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h4>Thông tin chi tiết slide</h4>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $slide->slide_id }}</p>
                <p><strong>Tên slide:</strong> {{ $slide->name }}</p>
                <p><strong>Ngày tạo:</strong> {{ $slide->created_at->format('d/m/Y') }}</p>

                @if ($slide->image)
                    <div>
                        <strong>Hình ảnh:</strong><br>
                        <img src="{{ asset($slide->image) }}" alt="Ảnh slide" class="img-thumbnail"
                            style="max-width: 300px;">
                    </div>
                @else
                    <p><strong>Hình ảnh:</strong> Không có ảnh</p>
                @endif
            </div>
        </div>
    </div>
@endsection
