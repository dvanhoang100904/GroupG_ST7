@extends('admin.layout.app')

@section('content')
    <main class="flex-grow-1 p-4 bg-light">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none text-dark"
                            href="{{ route('order.list') }}?page={{ request()->get('page') }}">
                            Đơn Hàng
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Chỉnh Sửa
                    </li>
                </ol>
            </nav>
            <h3 class="mb-4">Cập Nhật Đơn Hàng #1001</h3>
            <form action="{{ route('order.update', $order->order_id) }}?page={{ request()->get('page') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <!-- status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng Thái Đơn Hàng</label>
                        <select name="status" id="status" class="form-select form-select-lg" required>
                            <option value="chờ_xử_lý" {{ old('status', $order->status) == 'chờ_xử_lý' ? 'selected' : '' }}>
                                Chờ xử lý</option>
                            <option value="đang_xử_lý"
                                {{ old('status', $order->status) == 'đang_xử_lý' ? 'selected' : '' }}>Đang xử lý
                            </option>
                            <option value="đang_vận_chuyển"
                                {{ old('status', $order->status) == 'đang_vận_chuyển' ? 'selected' : '' }}>Đang vận
                                chuyển
                            </option>
                            <option value="đã_giao_hàng"
                                {{ old('status', $order->status) == 'đã_giao_hàng' ? 'selected' : '' }}>
                                Đã giao</option>
                            <option value="đã_hủy" {{ old('status', $order->status) == 'đã_hủy' ? 'selected' : '' }}>Đã
                                hủy</option>
                        </select>
                        @if ($errors->has('status'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('status') }}
                            </div>
                        @endif
                    </div>

                    <!-- payment_status -->
                    <div class="col-md-6">
                        <label for="payment_status" class="form-label">Trạng Thái Thanh Toán</label>
                        <select name="payment_status" id="payment_status" class="form-select form-select-lg" required>
                            <option value="đang_chờ"
                                {{ old('payment_status', $order->payment->status) == 'đang_chờ' ? 'selected' : '' }}>
                                Đang chờ</option>
                            <option value="đã_thanh_toán"
                                {{ old('payment_status', $order->payment->status) == 'đã_thanh_toán' ? 'selected' : '' }}>
                                Đã thanh toán</option>
                            <option value="thất_bại"
                                {{ old('payment_status', $order->payment->status) == 'thất_bại' ? 'selected' : '' }}>Thất
                                bại</option>
                            <option value="đã_hoàn_tiền"
                                {{ old('payment_status', $order->payment->status) == 'đã_hoàn_tiền' ? 'selected' : '' }}>Đã
                                hoàn tiền</option>
                        </select>
                        @if ($errors->has('payment_status'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('payment_status') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- payment_method -->
                <div class="mb-3 col-md-6">
                    <label for="payment_method" class="form-label">Phương Thức Thanh Toán</label>
                    <select name="payment_method" id="payment_method" class="form-select form-select-lg" required>
                        <option value="COD"
                            {{ old('payment_method', $order->payment->method) == 'COD' ? 'selected' : '' }}>COD
                        </option>
                        <option value="MoMo"
                            {{ old('payment_method', $order->payment->method) == 'MoMo' ? 'selected' : '' }}>MoMo
                        </option>
                    </select>
                    @if ($errors->has('payment_method'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('payment_method') }}
                        </div>
                    @endif
                </div>

                <!-- action -->
                <div class="mt-4 d-flex gap-3">
                    <a href="{{ route('order.list') }}?page={{ request()->get('page') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
