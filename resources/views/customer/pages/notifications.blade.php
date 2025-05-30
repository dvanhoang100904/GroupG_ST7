@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Danh sách thông báo</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Nút bật popup --}}
    <button id="showNotificationsBtn" class="btn btn-primary mb-3">
        Thông báo ({{ method_exists($notifications, 'total') ? $notifications->total() : $notifications->count() }})
    </button>

    {{-- Popup thông báo --}}
    <div id="notificationsPopup" style="display:none; position:fixed; top:50px; right:50px; width:350px; max-height:400px; overflow-y:auto; background:#fff; border:1px solid #ccc; padding:15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index:9999;">
        <h4>Thông báo mới</h4>
        <ul style="list-style:none; padding-left:0; margin:0;">
            @foreach ($notifications as $notification)
                <li style="border-bottom:1px solid #eee; padding:8px 0;">
                    <strong>{{ \Illuminate\Support\Str::limit($notification->title ?? 'Không có tiêu đề', 40) }}</strong><br>

                    <em>{{ \Illuminate\Support\Str::limit($notification->content ?? 'Không có nội dung', 60) }}</em><br>
                    <small class="text-muted">
                        {{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y H:i') : 'Chưa xác định' }}
                    </small>
                </li>
            @endforeach
        </ul>
        <button id="closePopupBtn" class="btn btn-sm btn-secondary mt-2">Đóng</button>
    </div>
    

    {{-- Danh sách full và phân trang --}}
    <ul class="mt-4">
        @foreach ($notifications as $notification)
            <li>
                {{ $notification->content ?? 'Không có nội dung' }} <br>
                <small>{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y H:i') : 'Chưa xác định' }}</small>
            </li>
        @endforeach
    </ul>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $notifications->links() }}
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showBtn = document.getElementById('showNotificationsBtn');
        const popup = document.getElementById('notificationsPopup');
        const closeBtn = document.getElementById('closePopupBtn');

        if(showBtn && popup && closeBtn) {
            showBtn.addEventListener('click', () => {
                popup.style.display = 'block';
            });

            closeBtn.addEventListener('click', () => {
                popup.style.display = 'none';
            });
        }
    });
</script>
@endsection
