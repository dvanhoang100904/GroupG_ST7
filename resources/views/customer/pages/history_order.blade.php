@extends('customer.layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Lịch sử mua hàng</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-shopping-cart me-2"></i>Tiếp tục mua sắm
            </a>
        </div>

        @if ($orders->count())
            <div class="accordion" id="ordersAccordion">
                @foreach ($orders as $order)
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse"
                                data-bs-target="#order-{{ $order->order_id }}" aria-expanded="false">
                                <div class="d-flex justify-content-between w-100 pe-3">
                                    <div>
                                        <span
                                            class="badge me-2 
                                            @switch($order->status)
                                                @case('completed') bg-success @break
                                                @case('processing') bg-primary @break
                                                @case('cancelled') bg-danger @break
                                                @default bg-secondary
                                            @endswitch">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                        <strong>Đơn hàng #{{ $order->order_id }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-muted me-3">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        <span
                                            class="fw-bold text-primary">{{ number_format($order->total_price, 0, ',', '.') }}
                                            ₫</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div id="order-{{ $order->order_id }}" class="accordion-collapse collapse"
                            data-bs-parent="#ordersAccordion">
                            <div class="accordion-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">STT</th>
                                                <th>Sản phẩm</th>
                                                <th width="120" class="text-center">Số lượng</th>
                                                <th width="150" class="text-end">Đơn giá</th>
                                                <th width="150" class="text-end">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($order->orderDetails as $index => $detail)
                                                <tr>
                                                    <td>{{ $detail->product->product_id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($detail->product->image) }}"
                                                                alt="{{ $detail->product->product_name }}"
                                                                class="img-thumbnail me-3" width="60">
                                                            <div>
                                                                <div class="fw-bold">
                                                                    {{ $detail->product->product_name ?? 'Sản phẩm không tồn tại' }}
                                                                </div>
                                                                @if ($detail->product->product_id)
                                                                    <small class="text-muted">SKU:
                                                                        {{ $detail->product->product_id }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $detail->quantity }}</td>
                                                    <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }} ₫
                                                    </td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}
                                                        ₫</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        <i class="fas fa-box-open fa-2x mb-2"></i>
                                                        <p class="mb-0">Không có sản phẩm nào trong đơn này</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="p-3 bg-light border-top">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Thông tin giao hàng</h6>
                                            <p class="mb-1">{{ $order->shippingAddress->address }}</p>
                                            <p class="mb-1">{{ $order->shippingAddress->phone }}</p>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <h6 class="fw-bold">Tổng cộng</h6>
                                            <div class="d-flex justify-content-between justify-content-md-end">
                                                <span class="me-4">Tạm tính:</span>
                                                <span>{{ number_format($order->total_price, 0, ',', '.') }}
                                                    ₫</span>
                                            </div>
                                            <div class="d-flex justify-content-between justify-content-md-end">
                                                <span class="me-4">Phí vận chuyển:</span>
                                                <span>0₫</span>
                                            </div>
                                            <div class="d-flex justify-content-between justify-content-md-end mt-2">
                                                <span class="me-4 fw-bold">Tổng tiền:</span>
                                                <span
                                                    class="fw-bold text-primary fs-5">{{ number_format($order->total_price, 0, ',', '.') }}
                                                    ₫</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{-- {{ $orders->links() }} --}}
            </div>
        @else
            <div class="text-center py-5 bg-light rounded-3">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h4 class="fw-bold mb-3">Bạn chưa có đơn hàng nào</h4>
                <p class="text-muted mb-4">Hãy khám phá cửa hàng của chúng tôi và tìm kiếm sản phẩm ưa thích</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-store me-2"></i> Mua sắm ngay
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, .125);
        }

        .table th {
            font-weight: 500;
        }
    </style>
@endpush
