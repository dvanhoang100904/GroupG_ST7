@extends('admin.layout.app')
@section('page_title', 'Chi tiết đánh giá khách hàng')

@section('content')
<div class="review-card border p-3 rounded shadow-sm mb-3">
    <strong>{{ $review->user->user_name ?? 'Ẩn danh' }}</strong>
    <div>⭐ {{ $review->rating }} / 5</div>
    <p>{{ $review->content }}</p>

    @if($review->photo)
        <img src="{{ asset($review->photo) }}" alt="Ảnh đánh giá" style="max-width: 150px;">
    @endif

    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>

    <!-- Form trả lời -->
    @if($review->type !== 'reply')
        <form method="POST" action="{{ route('admin.reviews.reply', $review->review_id) }}" class="mt-3">
            @csrf
            <div class="mb-2">
                <label for="reply_content" class="form-label">Phản hồi:</label>
                <textarea name="reply_content" id="reply_content" class="form-control" rows="2" placeholder="Nhập nội dung phản hồi..."></textarea>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Gửi phản hồi</button>
        </form>
    @endif

    <!-- Danh sách phản hồi -->
    @if ($review->replies->count())
        <div class="mt-4 border-top pt-3">
            <h6>Phản hồi:</h6>
            @foreach($review->replies as $reply)
                <div class="border-start ps-3 mb-2">
                    <strong class="text-danger">Admin:</strong>
                    <p>{{ $reply->content }}</p>
                    <small class="text-muted">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
