@extends('admin.layout.app')

@section('content')
    <main class="flex-grow-1 p-4 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form action="{{ route('order.list') }}?page={{ request()->get('page') }}" method="GET" class="w-100">
                <input type="text" class="form-control w-25" name="q" placeholder="Tìm kiếm đơn hàng..."
                    value="{{ request()->q }}" />
            </form>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="h4 mb-0 fw-bold">
                <i class="fas fa-shopping-cart me-2"></i>Danh Sách Đơn Hàng
            </h2>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80" class="text-center">Mã</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Thanh Toán</th>
                        <th>Số Điện Thoại</th>
                        <th width="180" class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            {{-- id --}}
                            <td class="text-center fw-bold">#{{ $order->order_id }}</td>
                            {{-- name --}}
                            <td>{{ $order->user->name }}</td>
                            {{-- total --}}
                            <td>{{ number_format($order->total_price, 0, ',', '.') }} VND</td>
                            {{-- status --}}
                            <td>{{ $order->status }}</td>
                            {{-- payment method --}}
                            <td>{{ $order->payment->method }}</td>
                            {{-- phone --}}
                            <td class="text-center">{{ $order->user->phone }}</td>
                            {{-- action --}}
                            <td class="d-flex justify-content-center gap-3">
                                <a href="#!" class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#!" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#!" class="btn btn-sm btn-danger" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Phân trang -->
        @include('admin.layout.pagination', ['paginator' => $orders])
    </main>
@endsection
