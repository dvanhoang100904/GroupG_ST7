@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('reviews.update', $review->review_id) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <input type="hidden" name="product_id" value="{{ $review->product_id }}">

    <div class="mb-3">
        <label for="rating" class="form-label">Đánh giá (1-5 sao)</label>
        <select name="rating" id="rating" class="form-select" required>
            <option value="">-- Chọn sao --</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} sao</option>
            @endfor
        </select>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Mô tả đánh giá</label>
        <textarea name="content" id="content" class="form-control" rows="4" required>{{ $review->content }}</textarea>
    </div>

    <div class="mb-3">
        <label for="photo" class="form-label">Ảnh (Tùy chọn)</label>
        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">

        @if ($review->photo)
            <div class="mt-2">
                <img src="{{ asset($review->photo) }}" alt="Ảnh hiện tại" style="max-width: 200px;">
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-success">Cập nhật đánh giá</button>
</form>
