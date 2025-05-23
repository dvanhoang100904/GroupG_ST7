@extends('customer.layouts.app')
@section('title', 'Cảm ơn bạn đã đặt hàng #' . $order->order_id)
@section('content')
    <div class="container py-5">
        <div class="card shadow-sm border-success">
            <div class="card-body text-center py-4">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#28a745"
                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>
                </div>
                <h2 class="text-success mb-3">🎉 Đặt hàng thành công!</h2>
                <p class="lead">Cảm ơn bạn đã tin tưởng và mua sắm tại cửa hàng chúng tôi</p>

                <div class="order-summary bg-light p-4 rounded mb-4 text-start mx-auto" style="max-width: 500px;">
                    <h5 class="mb-3">Thông tin đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Mã đơn hàng:</span>
                        <strong>#{{ $order->order_id }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền:</span>
                        <strong class="text-danger">{{ number_format($order->total_price, 0, ',', '.') }} VND</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phương thức thanh toán:</span>
                        <strong>{{ ucfirst($order->payment->method) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Trạng thái:</span>
                        <strong class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong>
                    </div>
                </div>

                <div class="mt-4">
                    <p>Chúng tôi sẽ gửi thông báo qua email khi đơn hàng được cập nhật</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-white">
                <h4 class="mb-0">Chi tiết đơn hàng</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $detail)
                                <tr>
                                    <td class="text-start">
                                        <div class="d-flex align-items-center">
                                            @if ($detail->product->image)
                                                <img src="{{ asset($detail->product->image) }}"
                                                    alt="{{ $detail->product->product_name }}" width="50"
                                                    class="me-3">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $detail->product->product_name }}</h6>
                                                @if ($detail->product->sku)
                                                    <small class="text-muted">SKU: {{ $detail->product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                                    <td>{{ number_format($detail->total_price, 0, ',', '.') }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td><strong>{{ number_format($order->total_price, 0, ',', '.') }} VND</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
