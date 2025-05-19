@extends('customer.layouts.app')

@section('title', 'Quản lý địa chỉ nhận hàng')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-map-marker-alt me-2"></i>Địa chỉ nhận hàng
            </h2>
            <a href="{{ route('shipping_address.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
            </a>
        </div>

        @if ($addresses->count())
            <div class="row g-4">
                @foreach ($addresses as $address)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0 fw-bold">
                                        Địa chỉ #{{ $address->shipping_address_id }}

                                    </h5>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="addressDropdown{{ $address->shipping_address_id }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu"
                                            aria-labelledby="addressDropdown{{ $address->shipping_address_id }}">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('shipping_address.edit', $address->shipping_address_id) }}">
                                                    <i class="fas fa-edit me-2 text-primary"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form
                                                    action="{{ route('shipping_address.destroy', $address->shipping_address_id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Bạn có chắc muốn xoá địa chỉ này không?')">
                                                        <i class="fas fa-trash-alt me-2 text-danger"></i>
                                                    </button>
                                                </form>
                                            </li>

                                        </ul>
                                    </div>
                                </div>

                                <div class="address-details">
                                    <div class="mb-2">
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        <span class="fw-medium">{{ $address->name ?? 'Không có tên người nhận' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        <span>{{ $address->phone }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                        <span>{{ $address->address }}</span>
                                    </div>
                                    @if ($address->note)
                                        <div class="mt-3 p-2 bg-light rounded">
                                            <small class="text-muted">
                                                <i class="fas fa-sticky-note me-1"></i> Ghi chú: {{ $address->note }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 bg-light rounded-3">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h4 class="fw-bold mb-3">Bạn chưa có địa chỉ nhận hàng</h4>
                <p class="text-muted mb-4">Thêm địa chỉ mới để trải nghiệm mua sắm thuận tiện hơn</p>
                <a href="{{ route('shipping_address.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .address-details div {
            padding: 0.25rem 0;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            cursor: pointer;
        }
    </style>
@endpush
