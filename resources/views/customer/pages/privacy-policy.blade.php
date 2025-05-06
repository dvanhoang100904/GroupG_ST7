@extends('customer.layouts.app')

@section('content')
<div class="container my-4">

    {{-- Banner đầu trang --}}
    <div class="banner-wrapper mb-4">
        <img src="{{ asset('images/banner2.jpg') }}" alt="Chính sách bảo mật" class="banner-img">
    </div>

    {{-- Nội dung chính sách bảo mật --}}
    <section class="policy-content">
        <h2 class="text-uppercase">Chính sách bảo mật</h2>
        <p>Chúng tôi cam kết bảo vệ thông tin cá nhân của khách hàng. Mọi thông tin thu thập được chỉ phục vụ cho mục đích mua bán, giao hàng và hỗ trợ khách hàng.</p>
        <p>Thông tin cá nhân sẽ không được chia sẻ cho bên thứ ba nếu không có sự đồng ý của bạn, trừ khi được yêu cầu bởi pháp luật.</p>
        <p>Khách hàng có quyền yêu cầu xem, chỉnh sửa hoặc xóa thông tin cá nhân bất cứ lúc nào.</p>
    </section>

</div>

{{-- CSS nội tuyến hoặc có thể đưa vào file app.css --}}
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
