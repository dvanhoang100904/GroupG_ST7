@extends('customer.layouts.app')

@section('content')
<div class="container my-4">

    {{-- Banner đầu trang --}}
    <div class="banner-wrapper mb-4">
        <img src="{{ asset('images/banner3.jpg') }}" alt="Chính sách bảo hành" class="banner-img">
    </div>

    {{-- Nội dung chính sách bảo hành --}}
    <section class="policy-content">
        <h2 class="text-uppercase">Chính sách bảo hành</h2>
        <p>Sản phẩm được bảo hành trong vòng 12 tháng kể từ ngày mua, áp dụng với lỗi kỹ thuật do nhà sản xuất.</p>
        <p>Chúng tôi không bảo hành trong trường hợp sản phẩm bị rơi vỡ, ngấm nước, hoặc tác động vật lý từ người dùng.</p>
        <p>Vui lòng giữ hóa đơn mua hàng để được hỗ trợ bảo hành nhanh chóng và thuận tiện nhất.</p>
    </section>

</div>

{{-- CSS nội tuyến hoặc tách ra file riêng --}}
<style>
    .banner-wrapper {
        text-align: center;
    }

    .banner-img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .policy-content {
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
        line-height: 1.8;
        color: #333;
    }

    .policy-content h2 {
        color: #c80000;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .policy-content p {
        font-size: 1rem;
        margin-bottom: 1rem;
    }
</style>
@endsection
