@extends('customer.layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2 class="text-danger">Thông báo</h2>
    <p class="mt-3 fs-5">Hiện tại danh mục đang được cập nhật hoặc không còn tồn tại.</p>
    <p>Xin quý khách thông cảm và vui lòng quay lại sau.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-4">Về trang chủ</a>
</div>
@endsection
