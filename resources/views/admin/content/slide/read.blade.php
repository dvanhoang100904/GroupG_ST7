@extends('admin.layout.app')

@section('page_title', 'Chi Tiết Slide')

@section('content')
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('slide.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về danh sách
            </a>
            <a href="{{ route('slide.edit', $slide) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Sửa slide
            </a>           
            <form action="{{ route('slide.toggleVisibility', $slide->slide_id) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('PUT')
                <button class="btn {{ $slide->is_visible ? 'btn-success' : 'btn-secondary' }}">
                    <i class="fas fa-eye"></i> {{ $slide->is_visible ? 'Hiện' : 'Ẩn' }}
                </button>
            </form>         
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
                        @php
                            $imagePath = public_path($slide->image);
                            $imageUrl = file_exists($imagePath) ? asset($slide->image) : asset('images/default/upload.png');
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Slide" class="img-fluid" style="max-height: 200px;">

                        {{-- <img src="{{ asset($slide->image) }}" alt="Ảnh slide" class="img-thumbnail"
                            style="max-width: 300px;"> --}}
                    </div>
                @else
                    <p><strong>Hình ảnh:</strong> Không có ảnh</p>
                @endif
            </div>
        </div>
    </div>
    <script>
    setTimeout(() => {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
        }
    }, 3000);// 3s
</script>
@endsection
