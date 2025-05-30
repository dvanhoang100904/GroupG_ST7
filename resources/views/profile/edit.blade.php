@extends('customer.layouts.app')

@section('title', 'Cập nhật thông tin cá nhân')

@section('content')
<div class="py-5">
    <div class="container" style="max-width: 700px; margin: auto;">
        {{-- Cập nhật thông tin hồ sơ --}}
           
                <div class="card-body">
                    
                        @include('profile.partials.profile')
                    
            
                    <div id="profile-edit" style="display: none;">
                        @include('profile.partials.update-profile-information-form')

                        <button id="btn-cancel-edit" class="btn btn-secondary mt-3">
                            Hủy
                        </button>
                    </div>
                </div>
        
        {{-- Đổi mật khẩu --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">Đổi mật khẩu</h4>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Xóa tài khoản --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3 text-danger">Xóa tài khoản</h4>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('btn-edit-profile').addEventListener('click', function() {
        document.getElementById('profile-view').style.display = 'none';
        document.getElementById('profile-edit').style.display = 'block';
    });

    document.getElementById('btn-cancel-edit').addEventListener('click', function() {
        document.getElementById('profile-edit').style.display = 'none';
        document.getElementById('profile-view').style.display = 'block';
    });
});
</script>

