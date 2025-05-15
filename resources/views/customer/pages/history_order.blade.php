@extends('layouts.app')

@section('content')
<h2>Lịch sử mua hàng của bạn</h2>

@if($orders->isEmpty())
    <p>Bạn chưa có đơn hàng nào.</p>
@else
    @foreach($orders as $order)
        <div class="order-card" style="border:1px solid #ddd; padding:10px; margin-bottom:15px;">
            <h4>Đơn hàng #{{ $order->order_id }} - {{ $order->created_at->format('d/m/Y H:i') }}</h4>
            <p>Trạng thái: <strong>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong></p>
            <p>Tổng tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</strong></p>

            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->unit_price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ number_format($detail->quantity * $detail->unit_price, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
@endif

@endsection
