@extends('admin.layout.app')
@section('page_title', 'Quản Lý Website')
@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Danh sách người đã đánh giá</h4>

        <!-- Form lọc -->
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3 mb-4">
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

        <!-- Danh sách đánh giá -->
        @foreach($reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div><strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong></div>
                    <div class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                    <div class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : '-o' }}"></i>
                        @endfor
                    </div>
                    <p>{{ $review->content }}</p>
                    @if ($review->photo)
                        <img src="{{ asset($review->photo) }}" alt="Ảnh đánh giá" class="img-thumbnail" style="max-width: 200px;">
                    @endif
                    <div class="mt-3">
                        <small class="text-muted">Phân loại: <strong>{{ $review->type }}</strong></small>
                    </div>
                    <!-- Nút phản hồi -->
                    @if($review->type !== 'reply')
                        <button class="btn btn-sm btn-outline-secondary mt-2 reply-btn"
                            data-review-id="{{ $review->review_id }}">Phản hồi</button>
                        <!-- Form phản hồi ẩn -->
                        <form method="POST" class="reply-form mt-3 d-none" data-review-id="{{ $review->review_id }}">
                            @csrf
                            <div class="mb-2">
                                <textarea name="reply_content" class="form-control" rows="2"
                                    placeholder="Nhập phản hồi..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success">Gửi</button>
                        </form>
                    @endif
                    <!-- Hiển thị các phản hồi -->
                    @if ($review->replies->count())
                        <div class="mt-3 ps-3 border-start">
                            @foreach ($review->replies as $reply)
                                <div class="mb-2">
                                    <strong>{{ $reply->user->name ?? 'Admin' }}:</strong>
                                    {{ $reply->content }}
                                    <div class="text-muted small">{{ $reply->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
@endsection