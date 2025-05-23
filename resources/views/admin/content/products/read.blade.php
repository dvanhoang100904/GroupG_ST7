@extends('admin.layout.app') 
{{-- Kế thừa layout chung của phần admin --}}

@section('page_title', 'Chi Tiết Sản Phẩm') 
{{-- Đặt tiêu đề trang là "Chi Tiết Sản Phẩm" --}}

@section('content')
    <div class="container py-3"> {{-- Khung chứa nội dung chính với padding trên dưới --}}

        {{-- Hiển thị thông báo thành công nếu có trong session --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }} {{-- Nội dung thông báo thành công --}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Nút đóng thông báo --}}
            </div>
        @endif

        <div class="mb-4">
            {{-- Nút quay lại danh sách sản phẩm --}}
            <a href="{{ route('products.list') }}" class="btn btn-secondary">Quay lại</a>
            {{-- Nút chuyển sang trang sửa sản phẩm --}}
            <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning">Sửa</a>
        </div>

        <div class="card shadow"> {{-- Thẻ card với hiệu ứng đổ bóng --}}
            <div class="card-header">
                <h4>Thông tin chi tiết sản phẩm</h4> {{-- Tiêu đề phần thông tin --}}
            </div>
            <div class="card-body">
                {{-- Hiển thị các thông tin chi tiết sản phẩm --}}
                <p><strong>ID:</strong> {{ $product->product_id }}</p>
                <p><strong>Tên:</strong> {{ e($product->product_name) }}</p> {{-- Hàm e() để escape dữ liệu an toàn --}}
                <p><strong>Giá:</strong> {{ e($product->price) }}</p>
                <p><strong>Mô tả:</strong> {{ $product->description ? e($product->description) : 'Không có mô tả' }}</p> {{-- Nếu không có mô tả hiển thị text thay thế --}}
                <p><strong>Danh mục:</strong> {{ $product->category->category_name ?? 'Không xác định' }}</p> {{-- Hiển thị tên danh mục hoặc thông báo nếu null --}}

                <p><strong>Ảnh:</strong></p>
                {{-- Nếu có ảnh thì hiển thị, nếu không thì hiển thị dòng chữ "Không có ảnh" --}}
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" width="200" alt="{{ $product->product_name }}">
                @else
                    <p>Không có ảnh</p>
                @endif
            </div>
        </div>
    </div>
@endsection
