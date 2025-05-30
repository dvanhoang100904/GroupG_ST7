@extends('customer.layouts.app') {{-- Kế thừa layout chính dành cho giao diện khách hàng --}}

@section('title', 'Trang sản phẩm') {{-- Đặt tiêu đề cho trang là "Trang sản phẩm" --}}

@section('content') {{-- Bắt đầu nội dung chính của trang --}}
<div class="container">

    {{-- Nếu có thông báo lỗi trong session (ví dụ như lỗi khi thêm giỏ hàng, lỗi hệ thống...) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }} {{-- Hiển thị thông báo lỗi --}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    {{-- Tiêu đề lớn cho phần danh sách sản phẩm --}}
    <h2 class="product-heading">SẢN PHẨM</h2>

    {{-- Khu vực sắp xếp sản phẩm --}}
    <div class="sort-filter mb-3">
        <div class="sort-label">Sắp xếp:</div>

        {{-- Form gửi yêu cầu sắp xếp bằng phương thức GET --}}
        <form method="GET" id="sortForm">
            {{-- Nếu người dùng đang tìm kiếm thì giữ lại từ khóa trong form --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            {{-- Các nút sắp xếp: theo tên và giá, có thêm class active nếu đang được chọn --}}
            <button type="submit" name="sort" value="name_asc" class="sort-btn {{ request('sort') == 'name_asc' ? 'active' : '' }}">Tên A-Z</button>
            <button type="submit" name="sort" value="name_desc" class="sort-btn {{ request('sort') == 'name_desc' ? 'active' : '' }}">Tên Z-A</button>
            <button type="submit" name="sort" value="price_asc" class="sort-btn {{ request('sort') == 'price_asc' ? 'active' : '' }}">Giá thấp đến cao</button>
            <button type="submit" name="sort" value="price_desc" class="sort-btn {{ request('sort') == 'price_desc' ? 'active' : '' }}">Giá cao đến thấp</button>

            {{-- Nút để xóa sắp xếp, quay lại trạng thái mặc định --}}
            @if(request('sort'))
                <button type="submit" name="sort" value="" class="sort-btn reset-btn">Quay lại</button>
            @endif
        </form>
    </div>

    <div class="product-grid">
        {{-- Nếu không có sản phẩm nào được trả về --}}
        @if ($products->count() === 0)
            <div class="alert alert-warning text-center my-4">
                Không tìm thấy sản phẩm nào{{ $search ? ' với từ khóa: "' . $search . '"' : '' }}.
            </div>
        @endif

        {{-- Duyệt qua danh sách sản phẩm --}}
        @foreach ($products as $product)
            {{-- Mỗi sản phẩm là một thẻ liên kết đến trang chi tiết --}}
            <a href="{{ route('products.detail', ['slug' => $product->slug]) }}" class="product-card">
                
                {{-- Hiển thị hình ảnh sản phẩm --}}
                <div class="product-image">
                    @php
                        $imagePath = public_path($product->image); // Đường dẫn tuyệt đối tới ảnh sản phẩm
                        $categorySlug = \Illuminate\Support\Str::slug(optional($product->category)->category_name ?? 'mac-dinh'); // Tạo slug từ tên danh mục
                        $defaultImage = "images/{$categorySlug}/mac-dinh.jpg"; // Ảnh mặc định theo danh mục
                    @endphp

                    @if ($product->image && file_exists($imagePath))
                        <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                    @elseif (file_exists(public_path($defaultImage)))
                        <img src="{{ asset($defaultImage) }}" alt="Ảnh mặc định">
                    @else
                        <p class="text-muted">Không có ảnh</p>
                    @endif
                </div>

                <div class="product-info">
                    {{-- Tên sản phẩm --}}
                    <h3>{{ $product->product_name }}</h3>

                    {{-- Giá sản phẩm được định dạng VND --}}
                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                    {{-- Mô tả sản phẩm rút gọn (tối đa 100 ký tự) --}}
                    <p class="description">{{ Str::limit($product->description, 100) }}</p>

                    {{-- Nút yêu thích sản phẩm --}}
                    @php
                    $isFavorited = auth()->check() && optional($product->favorites)->contains('user_id', auth()->id());
                @endphp

                <form method="POST" action="{{ route('favorites.store', ['productId' => $product->product_id]) }}" style="display:inline;">
                    @csrf
                    <button class="favorite-btn" data-product-id="{{ $product->product_id }}" style="background:none; border:none; cursor:pointer;">
                        <i class="fa fa-heart" style="color: {{ $isFavorited ? 'red' : 'black' }};"></i>
                    </button>

                </form>


                    {{-- Nút thêm vào giỏ hàng --}}
                    <form method="POST" action="{{ route('cart.addToCart') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}" />
                        <input type="hidden" name="quantity" value="1" />
                        <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                    </form>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Hiển thị phân trang, giữ nguyên các query hiện tại (sort, search,...) --}}
    @include('customer.layouts.pagination', ['paginator' => $products->appends(request()->query())])

    {{-- Nút quay về đầu trang --}}
    <button id="scrollToTopBtn" title="Lên đầu trang">⬆</button>
</div>
@endsection

{{-- Đẩy đoạn mã JavaScript cuộn lên đầu trang vào cuối file --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("scrollToTopBtn");

        // Hiển thị nút khi người dùng cuộn xuống hơn 200px
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };

        // Khi nhấn nút thì cuộn lên đầu trang một cách mượt mà
        btn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endpush

{{-- Script xử lý logic yêu thích sản phẩm bằng AJAX --}}
@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.favorite-btn', function(e) {
        e.preventDefault(); // Ngăn form submit mặc định
        e.stopPropagation(); // Ngăn sự kiện lan ra thẻ cha (tránh click vào thẻ <a>)

        let btn = $(this);
        let icon = btn.find('i.fa-heart');
        let productId = btn.data('product-id');

        if (!productId) {
            alert('Không tìm thấy sản phẩm!');
            return;
        }

        // Kiểm tra xem đã được yêu thích chưa dựa trên màu icon (màu đỏ là đã yêu thích)
        let isFavorited = icon.css('color') === 'rgb(255, 0, 0)';

        if (isFavorited) {
            // Nếu đã yêu thích => Gửi AJAX DELETE để xóa
            $.ajax({
                url: '/favorites/' + productId,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function() {
                    icon.css('color', 'black');
                },
                error: function() {
                    alert('Xóa yêu thích thất bại. Thử lại nhé!');
                }
            });
        } else {
            // Nếu chưa yêu thích => Gửi AJAX POST để thêm
            $.post('/favorites/' + productId, {_token: '{{ csrf_token() }}'})
            .done(function() {
                icon.css('color', 'red');
            })
            .fail(function() {
                alert('Thêm yêu thích thất bại. Thử lại nhé!');
            });
        }
    });
});
</script>
@endpush
