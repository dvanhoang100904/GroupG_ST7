@extends('admin.layout.app')
@section('page_title', 'Chi tiết đánh giá khách hàng')
@section('content')
<!-- Hiển thị đánh giá chính -->
<div class="review-card border p-3 rounded shadow-sm mb-3">
    <strong>{{ $review->user->user_name ?? 'Ẩn danh' }}</strong>
    <div>⭐ {{ $review->rating }} / 5</div>
    <p>{{ $review->content }}</p>

    @if($review->photo)
        <img src="{{ asset($review->photo) }}" alt="Ảnh đánh giá" style="max-width: 150px;">
    @endif

    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>

    <!-- Nút mở form trả lời -->
    <button class="btn btn-danger btn-sm mt-2" onclick="toggleReplyForm({{ $review->id }})">Reply</button>

    <!-- Form trả lời -->
    <div id="reply-form-{{ $review->id }}" style="display: none; margin-left: 40px;" class="mt-2">
        <form onsubmit="submitReply(event, {{ $review->id }})">
            @csrf
            <textarea class="form-control mb-2" name="reply_content" rows="2" placeholder="Nhập phản hồi..."></textarea>
            <button type="submit" class="btn btn-primary btn-sm">Gửi</button>
        </form>
    </div>

    <!-- Hiển thị phản hồi nếu có -->
    @foreach($review->replies as $reply)
        <div class="border-start ps-3 mt-2" style="margin-left: 40px;">
            <strong class="text-danger">Admin</strong>
            <p>{{ $reply->content }}</p>
            <small class="text-muted">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
        </div>
    @endforeach
</div>
@section('script')
<script>
    function toggleReplyForm(reviewId) {
        const form = document.getElementById(`reply-form-${reviewId}`);
        if (form) {
            form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        }
    }

    function submitReply(event, reviewId) {
        event.preventDefault();
        const form = event.target;
        const textarea = form.querySelector('textarea');
        const replyText = textarea.value.trim();
        if (!replyText) return;

        const replyHTML = `
            <div class="border-start ps-3 mb-2">
                <strong>Admin</strong>
                <p>${replyText}</p>
                <small class="text-muted">Vừa xong</small>
            </div>
        `;

        const repliesContainer = document.getElementById(`replies-${reviewId}`);
        if (repliesContainer) {
            repliesContainer.innerHTML += replyHTML;
        }

        textarea.value = '';
        form.style.display = 'none';
    }
</script>
@endsection
@endsection