@extends('customer.layouts.app')
@section('title', 'Chỉnh sửa đánh giá')

@section('content')
<div class="container mt-5">
    <h3>Chỉnh sửa đánh giá</h3>

    <form action="{{ route('reviews.update', $review->review_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="rating">Đánh giá (sao):</label>
            <select name="rating" id="rating" class="form-control">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="content">Nội dung:</label>
            <textarea name="content" id="content" class="form-control" rows="4">{{ $review->content }}</textarea>
        </div>

        <div class="mb-3">
            <label for="photo">Ảnh minh họa (tuỳ chọn):</label><br>
            @if($review->photo)
                <img src="{{ asset($review->photo) }}" alt="Ảnh cũ" width="150"><br>
            @endif
            <input type="file" name="photo" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật đánh giá</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Huỷ</a>
    </form>
</div>
@endsection
    