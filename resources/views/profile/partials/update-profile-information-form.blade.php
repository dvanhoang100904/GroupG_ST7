<form method="POST" action="{{ route('customer.profile.update') }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Họ tên</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', auth()->user()->name) }}" 
               required 
               autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Số điện thoại</label>
        <input type="text" 
               id="phone" 
               name="phone" 
               class="form-control @error('phone') is-invalid @enderror" 
               value="{{ old('phone', auth()->user()->phone) }}" 
               required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" 
               id="email" 
               name="email" 
               class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', auth()->user()->email) }}" 
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <input type="text" 
               id="address" 
               name="address" 
               class="form-control @error('address') is-invalid @enderror" 
               value="{{ old('address', auth()->user()->address) }}">
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-2"></i> Lưu thay đổi
    </button>
</form>
