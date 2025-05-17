@extends('admin.layout.app')
@section('page_title', 'Chi tiết đánh giá khách hàng')
@section('content')
<div class="container mt-4">
  <h4 class="mb-4">Chi tiết đánh giá của khách hàng</h4>
  
  <div class="card mb-4">
    <div class="card-header bg-light fw-bold">Thông tin đánh giá</div>
    <div class="card-body row">
      <div class="col-md-6">
       <p><strong>Họ tên khách hàng:</strong> {{ $review->user->name }} (ID: {{ $review->user->user_id }})</p>
        <p><strong>Số điện thoại:</strong> {{ $review->user->phone ?? 'Không có' }}</p>
        <p><strong>Email:</strong> {{ $review->user->email }}</p>
      </div>
      <div class="col-md-6">
        <p><strong>Sản phẩm:</strong> {{ $review->product->name ?? 'Không rõ' }}</p>
        <p><strong>Loại:</strong> 
          @if ($review->type === 'chat')
            <span class="badge bg-info text-dark">Tin nhắn</span>
          @else
            <span class="badge bg-secondary">Bình luận</span>
          @endif
        </p>
        <p><strong>Số sao:</strong> {{ $review->rating }} / 5</p>
        <p><strong>Ngày đánh giá:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-light fw-bold">Nội dung đánh giá</div>
    <div class="card-body">
      <p>{{ $review->type === 'chat' ? $review->chat->description : $review->content }}</p>
    </div>
  </div>
</div>
@endsection
