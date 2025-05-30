@extends('admin.layout.app')

@section('page_title', 'Quản lý slides')

@section('content')
    <div class="container py-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
        @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
@endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('slide.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm slide
            </a>

            <!-- Ô tìm kiếm -->
            <div class="search-box">
                <form method="GET" action="{{ route('slide.index') }}" class="search-form">
                    <input type="text" name="search" placeholder="Tìm kiếm slide..." value="{{ request('search') }}">
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên slide</th>
                    <th>Hình ảnh</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($slides as $slide)
                    <tr>
                        <td>{{ $slide->slide_id }}</td>
                        <td>{{ $slide->name }}</td>
                        <td>
                            {{-- <img src="{{ asset($slide->image) }}" alt="Slide" class="img-fluid" style="max-height: 200px;"> --}}
                            @php
    $imagePath = public_path($slide->image);
    $imageUrl = file_exists($imagePath) ? asset($slide->image) : asset('images/default/upload.png');
@endphp
<img src="{{ $imageUrl }}" alt="Slide" class="img-fluid" style="max-height: 200px;">

                        </td>
                        <td>{{ $slide->created_at->format('d/m/Y') }}</td>
                        <td>
                             {{-- Nút Hiện/Ẩn --}}
                             <form action="{{ route('slide.toggleVisibility', $slide->slide_id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('PUT')
                                <button class="btn {{ $slide->is_visible ? 'btn-success' : 'btn-secondary' }} btn-sm">
                                    <i class="fas fa-eye"></i> {{ $slide->is_visible ? 'Hiện' : 'Ẩn' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('slide.read', $slide->slide_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            <a href="{{ route('slide.edit', $slide->slide_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('slide.destroy', $slide->slide_id) }}" method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa slide này?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có slide nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        @include('admin.layout.pagination', ['paginator' => $slides])
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
