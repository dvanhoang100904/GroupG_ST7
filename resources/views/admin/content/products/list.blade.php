@extends('admin.layout.app')
{{-- Kế thừa layout chung của admin --}}

@section('page_title', 'Quản Lý Sản Phẩm')
{{-- Thiết lập tiêu đề trang là "Quản Lý Sản Phẩm" --}}

@section('content')
    <div class="container py-3">
        {{-- Hiển thị thông báo thành công nếu có --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- Hiển thị thông báo lỗi nếu có --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Lỗi!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>

            <script>
                // Tự động reload trang sau 3 giây nếu có lỗi
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            </script>
        @endif

        {{-- Thanh công cụ: Nút thêm và ô tìm kiếm --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>

            {{-- Ô tìm kiếm sản phẩm --}}
            <div class="search-box">
                <form method="GET" action="{{ route('products.list') }}" class="search-form">
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Nếu có từ khóa tìm kiếm nhưng không có kết quả --}}
        @if(request('search') && $products->isEmpty())
            <div class="alert alert-warning">
                Không tìm thấy sản phẩm với từ khóa: <strong>{{ request('search') }}</strong>
            </div>
        @endif

        {{-- Bảng hiển thị danh sách sản phẩm --}}
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Danh mục</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                {{-- Lặp qua danh sách sản phẩm --}}
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->product_id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" width="60" alt="{{ $product->product_name }}">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $product->price ?? '—' }}</td>
                        <td>{{ $product->category->category_name ?? '—' }}</td>
                        <td>
                            {{-- Nút xem chi tiết sản phẩm --}}
                            <a href="{{ route('products.read', $product->product_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>

                            {{-- Nút chỉnh sửa sản phẩm --}}
                            <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>

                            {{-- Form xóa sản phẩm --}}
                            <form action="{{ route('products.destroy', $product->product_id) }}" method="POST"
                                class="d-inline-block"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Nếu không có sản phẩm nào --}}
                    <tr>
                        <td colspan="6" class="text-center">Không có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang danh sách sản phẩm --}}
        @include('admin.layout.pagination', ['paginator' => $products])
    </div>
@endsection
