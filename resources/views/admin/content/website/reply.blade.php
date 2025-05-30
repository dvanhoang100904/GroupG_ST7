@extends('admin.layout.app')
@section('page_title', 'Phản hồi đánh giá')

@section('content')
<div class="review-card border p-3 rounded shadow-sm mb-3">
    <strong>{{ $review->user->user_name ?? 'Ẩn danh' }}</strong>
    <div>⭐ {{ $review->rating }} / 5</div>
    <p>{{ $review->content }}</p>

    @if($review->photo)
        <img src="{{ asset($review->photo) }}" alt="Ảnh đánh giá" style="max-width: 150px;">
    @endif

    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>

    @if($review->type !== 'reply')
    <form id="replyForm" method="POST" action="{{ route('admin.reviews.storeReply', $review->review_id) }}" class="mt-3">
        @csrf
        <div class="mb-2">
            <label for="reply_content" class="form-label">Phản hồi:</label>
            <textarea name="reply_content" id="reply_content" class="form-control" rows="4" placeholder="Nhập nội dung phản hồi..."></textarea>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Gửi phản hồi</button>
    </form>
    @endif
  <div id="tempReplyContainer" class="mt-3 border-top pt-3">
        <h6>Phản hồi </h6>
        <div id="tempRepliesList"></div>
    </div>
    <!-- Phản hồi đã có từ DB -->
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

<script>
    const reviewId = {{ $review->review_id }};
    const localStorageKey = `temp_replies_${reviewId}`; 
    const tempRepliesList = document.getElementById('tempRepliesList');
    const replyForm = document.getElementById('replyForm');
    const replyTextarea = document.getElementById('reply_content');

    function loadTempReplies() {
        const now = Date.now();
        const raw = localStorage.getItem(localStorageKey);
        let replies = [];
        if(raw){
            try {
                replies = JSON.parse(raw);
             
                replies = replies.filter(r => now - r.time < 18000000);
            } catch(e) {
                replies = [];
            }
        }

        localStorage.setItem(localStorageKey, JSON.stringify(replies));
        return replies;
    }

  
    function showTempReplies() {
        const replies = loadTempReplies();
        if(replies.length === 0){
            tempRepliesList.innerHTML = '<p><em>Chưa có phản hồi!</em></p>';
            return;
        }
        tempRepliesList.innerHTML = '';
        replies.forEach(reply => {
            const div = document.createElement('div');
            div.className = 'border-start ps-3 mb-2';
            div.innerHTML = `
                <strong class="text-warning">Admin :</strong>
                <p>${reply.content}</p>
                <small class="text-muted">Gửi lúc: ${new Date(reply.time).toLocaleString()}</small>
            `;
            tempRepliesList.appendChild(div);
        });
    }

    // Khởi đầu hiển thị
    showTempReplies();
    replyForm.addEventListener('submit', function(e){
        const content = replyTextarea.value.trim();
        if(content.length === 0){
            e.preventDefault();
            alert('Vui lòng nhập nội dung phản hồi.');
            return;
        }
        let replies = loadTempReplies();
        replies.push({
            content: content,
            time: Date.now()
        });
        // Lưu lại localStorage
        localStorage.setItem(localStorageKey, JSON.stringify(replies));

        // Hiển thị ngay
        showTempReplies();
    });

</script>
@endsection
