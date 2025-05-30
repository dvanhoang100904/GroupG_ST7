@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('reviews.store', ['productId' => $product->product_id]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->product_id }}">

    {{-- Rating --}}
    <div class="mb-3">
        <label for="rating" class="form-label">Đánh giá (1-5 sao)</label>
        <select name="rating" id="rating" class="form-select" required>
            <option value="">-- Chọn sao --</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
            @endfor
        </select>
        @error('rating')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Content --}}
    <div class="mb-3">
        <label for="content" class="form-label">Mô tả đánh giá</label>
        <textarea name="content" id="content" class="form-control" rows="4" required>{{ old('content') }}</textarea>
        @error('content')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Photo --}}
    <div class="mb-3">
        <label for="photo" class="form-label">Ảnh minh họa (tùy chọn)</label>
        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
        @error('photo')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>


    <button type="submit" class="btn btn-primary" id="submitBtn">Gửi đánh giá</button>
</form>

{{-- JS chống nhấn nút liên tục --}}
<script>
    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('submitBtn').disabled = true;
    });
</script>