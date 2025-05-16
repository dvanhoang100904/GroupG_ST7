@extends('customer.layouts.app') {{-- hoặc layout gốc mà form create đang dùng --}}
@section('title', 'Chỉnh sửa địa chỉ nhận hàng')

@section('content')
<div class="container">
    <h2 class="page-heading mb-4">Chỉnh sửa địa chỉ nhận hàng</h2>

    <form action="{{ route('shipping_address.update', $shippingAddress->shipping_address_id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Tên người nhận</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                   value="{{ old('name', $shippingAddress->name) }}" placeholder="Nhập tên người nhận" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label fw-semibold">Địa chỉ</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address"
                   value="{{ old('address', $shippingAddress->address) }}" placeholder="Nhập địa chỉ nhận hàng" required>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"
                   value="{{ old('phone', $shippingAddress->phone) }}" placeholder="Nhập số điện thoại" required pattern="[0-9]{9,11}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('shipping_address.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
