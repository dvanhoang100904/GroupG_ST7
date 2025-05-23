@extends('customer.layouts.app')
@section('title', 'Trang thanh toán ')
@section('content')
    <section class="bg-light py-5">
        <div class="container">
            <form action="{{ route('order.placeOrder') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xl-8 col-lg-8 mb-4">
                        <div class="card shadow-0 border">
                            <div class="p-4">
                                <h5 class="card-title mb-4">Thông tin đơn hàng</h5>
                                @foreach ($cartItems as $cartItem)
                                    <div class="row align-items-center mb-5">
                                        <div class="col-md-2">
                                            <img src="{{ asset($cartItem->product->image) }}" class="img-fluid rounded"
                                                alt="{{ $cartItem->product->product_name }}" />
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">{{ $cartItem->product->product_name }}</h6>
                                        </div>
                                        <div class="col-md-3">
                                            Số lượng: <b class="text-muted small">{{ $cartItem->quantity }}</b>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-0 fw-bold">
                                                {{ number_format($cartItem->product->price * $cartItem->quantity, 2) }} VND
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                                <hr class="my-4" />
                                <h5 class="card-title mb-4">Thông tin thanh toán</h5>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="name" type="text" name="name"
                                        placeholder="Tên người nhận" value="{{ old('name') }}" required />
                                    <label for="name">Tên</label>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="phone" type="tel" name="phone"
                                        placeholder="SĐT" value="{{ old('phone') }}" required />
                                    <label for="phone">SĐT</label>
                                    @if ($errors->has('phone'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control " id="email" type="email" name="email"
                                        placeholder="Email" value="{{ old('email') }}" required />
                                    <label for="email">Email</label>
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="address" type="text" name="address"
                                        placeholder="Địa chỉ giao hàng" value="{{ old('address') }}" required />
                                    <label for="address">Địa chỉ giao hàng</label>
                                    @if ($errors->has('address'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('address') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-outline mb-3">
                                    <label for="paymentMethod" class="mb-2">Phương thức thanh toán</label>
                                    <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                                        <option value="COD" {{ old('paymentMethod') == 'COD' ? 'selected' : '' }}>COD
                                        </option>
                                        <option value="MoMo" {{ old('paymentMethod') == 'MoMo' ? 'selected' : '' }}>
                                            MoMo</option>

                                    </select>
                                    @if ($errors->has('paymentMethod'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('paymentMethod') }}
                                        </div>
                                    @endif
                                </div>


                                <div class="form-floating">
                                    <textarea class="form-control" id="note" name="notes" placeholder="Nhập ghi chú"
                                        style="height: 140px; resize: none">{{ old('notes') }}</textarea>
                                    <label for="note">Ghi chú</label>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <a href="{{ route('cart.list') }}" class="btn btn-outline-secondary">
                                        <i class="fa fa-arrow-left me-1"></i> Quay lại giỏ hàng
                                    </a>
                                    <button type="submit" class="btn btn-success">Thanh toán</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tổng payment -->
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
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
