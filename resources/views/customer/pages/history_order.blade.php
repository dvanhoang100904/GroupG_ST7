@extends('customer.layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
<div class="container">
    <h2 class="page-heading mb-4">Lịch sử mua hàng của bạn</h2>

    @if($orders->count())
        @foreach($orders as $order)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong>Đơn hàng #{{ $order->order_id }}</strong>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Trạng thái:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ number_format($detail->quantity * $detail->unit_price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Không có sản phẩm nào trong đơn này.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
       
    @else
        <p>Chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay nhé!</p>
    @endif
</div>
@endsection
