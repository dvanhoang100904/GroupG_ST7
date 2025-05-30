@extends('admin.layout.app')
@section('page_title', 'Quản Lý Đơn Hàng')
@section('content')
    @php
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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

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
                    @forelse  ($orders as $order)
                        <tr>
                            {{-- id --}}
                            <td class="text-center fw-bold">#{{ $order->order_id }}</td>
                            {{-- name --}}
                            <td>{{ $order->shippingAddress->name ?? 'Chưa cập nhật' }}
                                <br>
                                <small class="text-muted">(Người Đặt: {{ $order->user->name ?? 'Không rõ' }})</small>
                            </td>
                            {{-- total --}}
                            <td>{{ number_format($order->total_price, 0, ',', '.') }} <sup>VND</sup></td>
                            {{-- status --}}
                            <td>
                                <span class="badge bg-{{ $orderStatuses[$order->status]['color'] ?? 'secondary' }}">
                                    {{ $orderStatuses[$order->status]['label'] ?? $order->status }}
                                </span>
                            </td>
                            {{-- payment method --}}
                            <td>{{ $order->payment->method ?? 'Chưa có' }}</td>
                            {{-- phone --}}
                            <td class="text-center">{{ $order->shippingAddress->phone ?? '-' }}
                                <br>
                                <small class="text-muted">(SĐT Người Đặt: {{ $order->user->phone ?? '-' }})</small>
                            </td>

                            {{-- action --}}
                            <td class="d-flex justify-content-center gap-3">
                                {{-- detail --}}
                                <a href="{{ route('order.detail', $order->order_id) }}?page={{ request()->get('page') }}"
                                    class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- edit --}}
                                <a href="{{ route('order.edit', $order->order_id) }}?page={{ request()->get('page') }}"
                                    class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- delete --}}
                                <form
                                    action="{{ route('order.delete', $order->order_id) }}?page={{ request()->get('page') }}"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?');"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" href="#!" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Chưa có đơn hàng nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Phân trang -->
        @include('admin.layout.pagination', ['paginator' => $orders])
    </main>
@endsection
