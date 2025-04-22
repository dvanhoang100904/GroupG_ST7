@extends('admin.layout.app')
@section('page_title', 'Quản Lý Đơn Hàng')
@section('content')
    @php
        $paymentStatuses = [
            'đang_chờ' => [
                'label' => 'Đang chờ',
                'color' => 'secondary',
            ],
            'đã_thanh_toán' => [
                'label' => 'Đã thanh toán',
                'color' => 'success',
            ],
            'thất_bại' => [
                'label' => 'Thất bại',
                'color' => 'danger',
            ],
            'đã_hoàn_tiền' => [
                'label' => 'Đã hoàn tiền',
                'color' => 'info',
            ],
        ];

        $orderStatuses = [
            'chờ_xử_lý' => [
                'label' => 'Chờ xử lý',
                'color' => 'secondary',
            ],
            'đang_xử_lý' => [
                'label' => 'Đang xử lý',
                'color' => 'warning',
            ],
            'đang_vận_chuyển' => [
                'label' => 'Đang vận chuyển',
                'color' => 'primary',
            ],
            'đã_giao_hàng' => [
                'label' => 'Đã giao hàng',
                'color' => 'success',
            ],
            'đã_hủy' => [
                'label' => 'Đã hủy',
                'color' => 'danger',
            ],
        ];
    @endphp
    <main class="flex-grow-1 p-4 bg-light">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none text-dark"
                            href="{{ route('order.list') }}?page={{ request()->get('page') }}">
                            Quản lý đơn hàng
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết </li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-3">
                <i class="fas fa-shopping-cart me-2"></i> Chi tiết đơn hàng #{{ $order->order_id }}
            </h3>
        </div>

        {{-- detail --}}
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">Thông tin đơn hàng</div>
            <div class="card-body row">
                <div class="col-md-6">
                    <p><strong>Mã đơn hàng:</strong> #{{ $order->order_id }}</p>
                    <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VND</p>
                    <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trạng thái đơn hàng:</strong> <span
                            class="badge bg-{{ $orderStatuses[$order->status]['color'] ?? 'secondary' }}">
                            {{ $orderStatuses[$order->status]['label'] ?? $order->status }}
                        </span></p>
                    <p><strong>Phương thức thanh toán:</strong> {{ $order->payment->method }}</p>
                    <p><strong>Trạng thái thanh toán:</strong> <span
                            class="badge bg-{{ $paymentStatuses[$order->payment->status]['color'] ?? 'secondary' }}">
                            {{ $paymentStatuses[$order->payment->status]['label'] ?? $order->payment->status }}
                        </span></p>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">Thông tin Giao Hàng</div>
            <div class="card-body row">
                <div class="col-md-6">
                    <p><strong>Tên Người Nhận:</strong> {{ $order->shippingAddress->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <div class="col-md-6">
                        <p><strong>Số điện thoại:</strong> {{ $order->shippingAddress->phone }}</p>
                        <p><strong>Địa chỉ giao hàng:</strong> {{ Str::limit($order->shippingAddress->address, 30) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">Danh sách sản phẩm</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">Tên</th>
                            <th class="text-center">Hình ảnh</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $orderDetail)
                            <tr class="text-center">
                                <td>
                                    @if ($orderDetail->product)
                                        {{ $orderDetail->product->name }}
                                    @else
                                        <span class="text-muted">[Đã xoá]</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($orderDetail->product && $orderDetail->product->image)
                                        <img src="{{ asset('storage/' . $orderDetail->product->image) }}"
                                            alt="{{ $orderDetail->product->name }}" width="50" class="rounded border">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $orderDetail->quantity }}</td>
                                <td>{{ number_format($orderDetail->price, 0, ',', '.') }} VND</td>
                                <td>{{ number_format($orderDetail->price * $orderDetail->quantity, 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Tổng tiền:</th>
                            <th class="text-center">{{ number_format($order->total_price, 0, ',', '.') }} VND</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- action -->
        <div class="mt-4 d-flex gap-3">
            <a href="{{ route('order.list') }}?page={{ request()->get('page') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>

            <a href="{{ route('order.edit', $order->order_id) }}?page={{ request()->get('page') }}"
                class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </main>
@endsection
