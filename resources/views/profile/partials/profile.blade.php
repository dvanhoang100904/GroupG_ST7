{{-- Cập nhật thông tin hồ sơ --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h4 class="card-title mb-3">Thông tin hồ sơ</h4>

        <div id="profile-view">
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item">
                    <strong>Họ tên:</strong> {{ auth()->user()->name }}
                </li>
                <li class="list-group-item">
                    <strong>Số điện thoại:</strong> {{ auth()->user()->phone }}
                </li>
                <li class="list-group-item">
                    <strong>Email:</strong> {{ auth()->user()->email }}
                </li>
                <li class="list-group-item">
                    <strong>Địa chỉ:</strong> {{ auth()->user()->address ?? 'Chưa cập nhật' }}
                </li>
            </ul>

            <button id="btn-edit-profile" class="btn btn-success">
                Cập nhật hồ sơ
            </button>
        </div>

        <div id="profile-edit" style="display:none;">
            @include('profile.partials.update-profile-information-form')

            <button id="btn-cancel-edit" class="btn btn-secondary mt-3">
                Hủy
            </button>
        </div>
    </div>
</div>

