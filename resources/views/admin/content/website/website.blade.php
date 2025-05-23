@extends('admin.layout.app')
@section('page_title', 'Quản Lý Website')
@section('content')

  <div class="container mt-4">
    <h4 class="mb-4">Danh sách người đã đánh giá</h4>

    <!-- Form lọc -->
    <form method="GET" action="{{ route('admin.website') }}" class="row g-3 mb-4">
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
    <div class="mb-4">
      <a href="{{ route('admin.reviews.index') }}" class="btn btn-yellow me-2">Danh Sách Đánh Giá</a>
      <a href="{{ route('admin.chats.index') }}" class="btn btn-black">Danh sách các tin nhắn</a>
    </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Chi tiết</th>
        <th>SDT</th>
        <th>Phân loại</th>
        <th>Chức năng</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($reviews as $index => $review)
      <tr id="review-row-{{ $review->review_id }}" data-review-id="{{ $review->review_id }}"
        @if(in_array($review->review_id, $tempConfirmedIds ?? [])) class="table-success" @endif>
        <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
        <td>
        <a href="{{ route('admin.reviews.detail', $review->user->user_id) }}" class="text-primary fw-bold">
        Chi tiết
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
        <button class="btn btn-warning btn-sm">Nhắn tin</button>
        <a href="{{ route('admin.reviews.reply', ['review' => $review->review_id]) }}"
        class="btn btn-danger btn-sm mt-2">Reply</a>
        <button class="btn btn-success btn-sm btn-confirm" data-bs-toggle="modal" data-bs-target="#confirmModal"
        data-review-id="{{ $review->review_id }}">
        Xác Nhận
        </button>
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
      <button type="button" class="btn btn-success" id="confirmReviewBtn">Xác nhận</button>
      </div>
    </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    let selectedReviewId = null;

    // Khi mở modal, lưu lại review ID được nhấn
    document.querySelectorAll('.btn-confirm').forEach(button => {
    button.addEventListener('click', function () {
      selectedReviewId = this.getAttribute('data-review-id');
    });
    });

    // Khi bấm xác nhận trong modal
    document.getElementById('confirmReviewBtn').addEventListener('click', function () {
    if (selectedReviewId) {
      // Gửi AJAX lưu tạm trạng thái xác nhận
      fetch('{{ route("admin.review.tempConfirm") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ review_id: selectedReviewId })
      })

      .then(res => res.json())
      .then(data => {
        if (data.success) {
        const row = document.getElementById('review-row-' + selectedReviewId);
        if (row) {
          row.classList.add('table-success'); // đổi màu
        }
        // Ẩn modal
        const modalEl = document.getElementById('confirmModal'); F
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        } else {
        alert('Xác nhận thất bại!');
        }
      })
      .catch(() => alert('Xác Nhận'));
    }
    });
  </script>
@endpush