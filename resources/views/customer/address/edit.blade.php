@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chỉnh sửa địa chỉ nhận hàng</h2>

    <form action="{{ route('shipping-address.update', $shippingAddress->shipping_address_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên người nhận</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $shippingAddress->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $shippingAddress->address) }}" required>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $shippingAddress->phone) }}" required>
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật địa chỉ</button>
        <a href="{{ route('shipping-address.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
