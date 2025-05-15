@extends('admin.layout.app')

@section('page_title', 'Quản Lý Tin Nhắn')

@section('content')
    <style>
        body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
    }

   .messenger {
  display: flex;
  height: 100vh;
  margin-left: 260px; /* Đẩy sang phải tránh đè sidebar */
}



 .sidebar {
  width: 300px;
  background: #fff;
  border-right: 1px solid #e0e0e0;
  overflow-y: auto;
  box-shadow: 2px 0 5px rgba(0,0,0,0.05);
  margin-top: 70px; /* thêm dòng này */
}


    .sidebar h2 {
      padding: 15px 20px;
      font-size: 20px;
      font-weight: 700;
      border-bottom: 1px solid #ddd;
    }

    .search {
      margin: 10px 20px;
      padding: 10px 15px;
      border-radius: 25px;
      border: 1px solid #ccc;
      width: calc(100% - 40px);
      font-size: 14px;
    }

    .chat-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .chat-item {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      gap: 12px;
      cursor: pointer;
      transition: background 0.2s ease;
      border-bottom: 1px solid #f1f1f1;
    }

    .chat-item:hover {
      background-color: #f5f7fa;
    }

    .chat-item img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
    }

    .chat-item strong {
      font-size: 15px;
      font-weight: 600;
    }

    .chat-item p {
      font-size: 13px;
      margin: 0;
      color: #777;
    }

    .chat-item.active {
      background-color: #e8f0ff;
    }

    main.flex-fill {
      display: flex;
      flex-direction: column;
      background-color: #edf1f7;
    }

    .chat-header {
      padding: 15px 20px;
      background: #fff;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chat-header img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
    }

    #chat-messages {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
    }

    .message-left,
    .message-right {
      display: flex;
      margin-bottom: 15px;
      align-items: flex-end;
    }

    .message-left {
      align-items: flex-start;
    }

    .message-left img {
      margin-right: 10px;
    }

    .message-right {
      justify-content: flex-end;
    }

    .message-box {
      padding: 10px 15px;
      border-radius: 18px;
      max-width: 65%;
      word-wrap: break-word;
      box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .message-left .message-box {
      background: #fff;
    }

    .message-right .message-box {
      background: #0084ff;
      color: #fff;
    }

    .message-box small {
      display: block;
      font-size: 11px;
      margin-top: 5px;
      color: rgba(255,255,255,0.7);
    }

    form {
      padding: 15px 20px;
      background: #fff;
      border-top: 1px solid #ddd;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    form input {
      border-radius: 20px;
      border: 1px solid #ccc;
      padding: 10px 15px;
    }

    form button {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    </style>

     <div class="messenger">
        <!-- Sidebar người dùng -->
        <aside class="sidebar">
            <h2><i class="fas fa-comment-dots me-2"></i>Đoạn chat</h2>
            <input type="text" class="search" placeholder="Tìm kiếm người dùng..." />

            <ul class="chat-list">
                @foreach ($users as $userItem)
                    @if ($userItem && $userItem->user_id)
                        <li class="chat-item {{ $userItem->user_id == $selectedUserId ? 'active' : '' }}">
                            <a href="{{ route('admin.chat.show', ['id' => $userItem->user_id]) }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark">
                                <img src="https://i.pravatar.cc/40?u={{ $userItem->user_id }}" alt="avatar">
                                <div>
                                    <strong>{{ $userItem->name }}</strong>
                                    <p>Đang hoạt động</p>
                                </div>
                            </a>
                        </li>
                    @else
                        <li class="chat-item text-danger">Không xác định được user</li>
                    @endif
                @endforeach
            </ul>
        </aside>

        <!-- Nội dung chat -->
        <main class="flex-fill">
            <!-- Header -->
            <div class="chat-header">
                @isset($user)
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://i.pravatar.cc/40?u={{ $user->id }}" alt="avatar">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <p class="mb-0 text-muted" style="font-size: 13px;">Đang hoạt động</p>
                        </div>
                    </div>
                @else
                    <div><strong>Chọn người dùng để bắt đầu trò chuyện</strong></div>
                @endisset

                <div>
                    <button class="btn btn-light"><i class="fas fa-phone-alt"></i></button>
                    <button class="btn btn-light"><i class="fas fa-video"></i></button>
                    <button class="btn btn-light"><i class="fas fa-info-circle"></i></button>
                </div>
            </div>

            <!-- Tin nhắn -->
            <div id="chat-messages">
                @foreach ($chats as $chat)
                    @php
                        $isAdmin = $chat->user_id === Auth::id();
                    @endphp
                    <div class="{{ $isAdmin ? 'message-right' : 'message-left' }}">
                        @if (!$isAdmin)
                            <img src="https://i.pravatar.cc/40?u={{ $chat->user_id }}" class="rounded-circle" width="40" height="40" />
                        @endif
                        <div class="message-box">
                            {{ $chat->description }}
                            <small>{{ $chat->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Form trả lời -->
            @if ($selectedUserId)
                <form method="POST" action="{{ route('admin.chat.reply', $selectedUserId) }}">
                    @csrf
                    <button class="btn btn-light"><i class="far fa-smile"></i></button>
                    <input type="text" name="description" class="form-control" placeholder="Nhập tin nhắn...">
                    <button class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                </form>
            @else
                <div class="alert alert-warning">Vui lòng chọn một người dùng để gửi tin nhắn.</div>
            @endif
        </main>
    </div>
@endsection