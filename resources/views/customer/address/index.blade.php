@extends('customer.layouts.app')

@section('title', 'Quản lý địa chỉ nhận hàng')

@section('content')
<div class="container">
    <h2 class="page-heading mb-4">Quản lý địa chỉ nhận hàng</h2>

    {{-- Nút thêm địa chỉ mới --}}
    <a href="{{ route('shipping_address.create') }}" class="btn btn-success mb-3">+ Thêm địa chỉ mới</a>

    @if($addresses->count())
    <table class="table table-bordered table-hover align-middle">
        <thead>
    <tr>
        <th>ID</th>
        <th>Địa chỉ</th>
        <th>Số điện thoại</th>
        <th>Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($addresses as $address)
            <tr>
                <td>{{ $address->shipping_address_id }}</td>
                <td>{{ $address->address }}</td>
                <td>{{ $address->phone }}</td>
                <td>
                    <a href="{{ route('shipping_address.edit', $address->shipping_address_id) }}" class="btn btn-warning btn-sm">Sửa</a>

                    <form action="{{ route('shipping_address.destroy', $address->shipping_address_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá địa chỉ này không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>

    </table>

    {{-- Phân trang nếu có --}}
    {{ $addresses->links() }}

    @else
    <p>Chưa có địa chỉ nhận hàng nào. Hãy thêm địa chỉ mới nhé!</p>
    @endif
</div>
@endsection
