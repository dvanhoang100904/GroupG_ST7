@extends('admin.layout.app')
@section('page_title', 'Quản Lý Website')
@section('content')
<div class="container mt-4">
  <h4 class="mb-4">Danh sách người đã đánh giá</h4>

  <!-- Form lọc -->
  <form method="GET" action="{{ route('admin.reviews') }}" class="row g-3 mb-4">
    <div class="col-md-3">
      <select name="type" class="form-select">
        <option value="">-- Tất cả phân loại --</option>
        <option value="review" {{ request('type') == 'review' ? 'selected' : '' }}>Bình luận</option>
        <option value="chat" {{ request('type') == 'chat' ? 'selected' : '' }}>Tin nhắn</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="rating" class="form-select">
        <option value="">-- Tất cả sao --</option>
        @for ($i = 1; $i <= 5; $i++)
          <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
        @endfor
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary">Lọc</button>
    </div>
  </form>

  <!-- Bảng danh sách -->
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Tên Khách Hàng</th>
          <th>SDT</th>
          <th>Phân loại</th>
          <th>Chi tiết</th>
          <th>Chức năng</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($reviews as $index => $review)
        <tr>
          <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
          <td>
            <a href="{{ route('admin.reviews.detail', $review->user->user_id) }}" class="text-primary fw-bold">
              {{ $review->user->name ?? 'Không rõ' }}
            </a>
          </td>
          <td>{{ $review->user->phone ?? 'Chưa có SĐT' }}</td>
          <td>
            @if ($review->type === 'chat')
              <span class="badge bg-info text-dark">Tin nhắn</span>
            @else
              <span class="badge bg-secondary">Bình Luận</span>
            @endif
          </td>
          <td>
            @if ($review->type === 'chat' && $review->chat)
              {{ $review->chat->description }}
            @else
              {{ $review->content }}
            @endif
          </td>
          <td>
            <button class="btn btn-warning btn-sm">Nhắn tin</button>
            <a href="{{ route('admin.reviews.reply', ['review' => $review->review_id]) }}" class="btn btn-danger btn-sm mt-2">Reply</a>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">Xác Nhận</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <div class="d-flex justify-content-center mt-4">
    @include('admin.layout.pagination', ['paginator' => $reviews])
  </div>
</div>

<!-- Modal Xác Nhận -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmModalLabel">Xác Nhận Đánh Giá</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xác nhận đánh giá này?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-success">Xác nhận</button>
      </div>
    </div>
  </div>
</div>
@endsection