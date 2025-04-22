@extends('admin.layout.app')

@section('page_title', 'Quản lý slides')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('slide.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm slide
            </a>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên slide</th>
                    <th>Hình ảnh</th>
                    <th>Ngày tạo</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($slides as $slide)
                    <tr>
                        <td>{{ $slide->slide_id }}</td>
                        <td>{{ $slide->name }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $slide->image) }}" alt="Slide" class="img-fluid" style="max-height: 200px;">
                        </td>
                        <td>{{ $slide->created_at->format('d/m/Y') }}</td>
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
        <div class="d-flex justify-content-center">
            {{ $slides->links() }}
        </div>
    </div>
@endsection
