@extends('customer.layouts.app')
@section('title', 'Trang giỏ hàng')
@section('content')
    @php
        $totalQuantity = $cartItems->sum('quantity');
    @endphp
    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <!-- List products -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Giỏ Hàng Của Bạn ({{ $totalQuantity }} sản phẩm)</h4>
                            @forelse ($cartItems as $cartItem)
                                @if ($cartItem->product)
                                    <div class="row align-items-center mb-5">
                                        <!-- image -->
                                        <div class="col-md-2">
                                            <img src="{{ $cartItem->product->image }}" class="img-fluid rounded"
                                                alt="{{ $cartItem->product->product_name }}" />
                                        </div>
                                        <!-- name -->
                                        <div class="col-md-4">
                                            {{ $cartItem->product->product_name }}
                                            <h6 class="mb-1">Đơn giá:
                                                {{ number_format($cartItem->product->price, 2) }} VND</h6>
                                        </div>
                                        <!-- quantity -->
                                        <div class="col-md-3">
                                            <div class="input-group" style="width: 150px">
                                                <button class="btn btn-outline-danger" type="button">
                                                    -
                                                </button>

                                                <!-- Hiển thị số lượng hiện tại -->
                                                <input type="text" class="form-control text-center"
                                                    value="{{ $cartItem->quantity }}" readonly style="width: 50px" />

                                                <button class="btn btn-outline-danger" type="button">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                        <!-- price -->
                                        <div class="col-md-2">
                                            <p class="mb-0 fw-bold">
                                                {{ number_format($cartItem->product->price * $cartItem->quantity, 2) }} VND
                                            </p>
                                        </div>
                                        <!-- action -->
                                        <div class="col-md-1">
                                            <form action="{{ route('cart.removeFromCart') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $cartItem->product_id }}">
                                                <button class="btn btn-link text-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        Một sản phẩm trong giỏ hàng không còn tồn tại.
                                    </div>
                                @endif
                            @empty
                                <div class="alert alert-info text-center">
                                    Giỏ hàng của bạn hiện đang trống.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Tổng Kết Đơn Hàng</h5>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Tổng tiền:</p>
                                    <p class="mb-2">{{ number_format($totalPrice, 2) }} VND</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Giảm giá:</p>
                                    <p class="mb-2 text-success">0 VND</p>
                                </div>

                                <hr />
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2 fw-bold">Tổng thanh toán:</p>
                                    <p class="mb-2 fw-bold">{{ number_format($totalPrice, 2) }} VND</p>
                                </div>
                            </div>

                            <!-- action -->
                            <a href="#!" class="btn btn-danger w-100">
                                Đặt hàng
                            </a>

                            <a href="{{ route('products.index') }}" class="btn btn-checkout w-100 mb-2">Tiếp tục mua
                                sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
