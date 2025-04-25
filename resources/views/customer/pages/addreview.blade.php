@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form action="{{ route('reviews.store', ['productId' => $product->product_id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->product_id }}">

    <div class="mb-3">
        <label for="rating" class="form-label">Đánh giá (1-5 sao)</label>
        <select name="rating" id="rating" class="form-select" required>
            <option value="">-- Chọn sao --</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} sao</option>
            @endfor
        </select>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Mô tả đánh giá</label>
        <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
        <label for="photo" class="form-label">Ảnh (Tùy chọn)</label>
        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
</form>
