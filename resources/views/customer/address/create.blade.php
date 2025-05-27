@extends('customer.layouts.app')

@section('title', 'Thêm địa chỉ nhận hàng')

@section('content')
<div class="container">
    {{-- Tiêu đề trang --}}
    <h2 class="page-heading mb-4">Thêm địa chỉ nhận hàng mới</h2>

    {{-- Form thêm địa chỉ --}}
    <form action="{{ route('shipping_address.store') }}" method="POST" novalidate>
        @csrf

        {{-- Tên người nhận --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Tên người nhận</label>
            <input 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                id="name" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                placeholder="Nhập tên người nhận"
                autofocus
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Địa chỉ --}}
        <div class="mb-3">
            <label for="address" class="form-label fw-semibold">Địa chỉ</label>
            <input 
                type="text" 
                class="form-control @error('address') is-invalid @enderror" 
                id="address" 
                name="address" 
                value="{{ old('address') }}" 
                required 
                placeholder="Nhập địa chỉ nhận hàng"
            >
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Số điện thoại --}}
        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
            <input 
                type="tel" 
                class="form-control @error('phone') is-invalid @enderror" 
                id="phone" 
                name="phone" 
                value="{{ old('phone') }}" 
                required 
                placeholder="Nhập số điện thoại"
                pattern="[0-9]{9,11}"
                title="Số điện thoại từ 9 đến 11 chữ số"
            >
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nút hành động --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
            <a href="{{ route('shipping_address.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
