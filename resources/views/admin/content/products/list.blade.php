@extends('admin.layout.app')

@section('page_title', 'Quản Lý Sản Phẩm')

@section('content')
    <div class="container py-3">

        {{-- Hiển thị thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>

            <!-- Ô tìm kiếm -->
            <div class="search-box">
                <form method="GET" action="{{ route('products.list') }}" class="search-form">
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Thông báo khi không tìm thấy sản phẩm --}}
        @if(request('search') && $products->isEmpty())
            <div class="alert alert-warning">
                Không tìm thấy sản phẩm với từ khóa: <strong>{{ request('search') }}</strong>
            </div>
        @endif

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
                            <!-- Chi tiết -->
                            <a href="{{ route('products.read', $product->product_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            <!-- Sửa -->
                            <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <!-- Xóa -->
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
                    <tr>
                        <td colspan="6" class="text-center">Không có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Phân trang -->
        @include('admin.layout.pagination', ['paginator' => $products])
    </div>
@endsection
