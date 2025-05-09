@extends('admin.layout.app')

@section('page_title', 'Quản Lý Tin Nhắn')

@section('content')
    <style>
        .chat-wrapper {
            display: flex;
            height: 80vh;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .chat-sidebar {
            width: 30%;
            background: #f5f5f5;
            overflow-y: auto;
            border-right: 1px solid #ddd;
        }

        .chat-content {
            width: 70%;
            padding: 20px;
            overflow-y: auto;
            position: relative;
        }

        .chat-user {
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.3s;
            border-bottom: 1px solid #e0e0e0;
        }

        .chat-user:hover {
            background: #e7e7e7;
        }

        .message-box {
            background: #f1f1f1;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            position: relative;
        }

        .message-box.reply {
            background: #d9f0ff;
            align-self: flex-end;
        }

        .message-time {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .reply-form {
            position: absolute;
            bottom: 20px;
            width: 100%;
            display: flex;
            gap: 10px;
        }

        .reply-form textarea {
            flex: 1;
            border-radius: 8px;
            resize: none;
        }

        .reply-form button {
            min-width: 80px;
        }
    </style>

    <div class="chat-wrapper">
        <!-- Sidebar người dùng -->
        <div class="chat-sidebar">
            <h5>Khách hàng</h5>
            <ul class="list-group">
                @foreach ($users as $user)
                    @if ($user && $user->user_id)
                        <li class="list-group-item">
                            <a href="{{ route('admin.chat.show', ['id' => $user->user_id]) }}" class="btn btn-link chat-user">
                                {{ $user->name }}
                            </a>
                        </li>
                    @else
                        <li class="list-group-item text-danger">Không xác định được user</li>
                    @endif
                @endforeach

            </ul>
        </div>

        <!-- Nội dung chat -->
        <div class="chat-content" id="chat-content">
            <div class="col-md-8">
                @isset($user)
                    <h5 id="chat-user-name">Tin nhắn với {{ $user->name }}</h5>
                @else
                    <h5 id="chat-user-name">Chọn người dùng để xem đoạn chat</h5>
                @endisset

                <div id="chat-messages" style="max-height: 400px; overflow-y: auto;"
                    class="mb-3 border rounded p-3 bg-light">
                    @foreach ($chats as $chat)
                        @php
                            $isAdmin = $chat->user_id === Auth::id(); // hoặc bạn kiểm tra role admin
                        @endphp

                        <div class="d-flex {{ $isAdmin ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="chat-bubble {{ $isAdmin ? 'chat-right' : 'chat-left' }}">
                                <strong>{{ $isAdmin ? 'Admin' : $chat->user->name }}</strong><br>
                                {{ $chat->description }}
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Form trả lời -->
                <form id="reply-form" method="POST" action="{{ route('admin.chat.reply', $selectedUserId) }}">
                    @csrf
                    <div class="input-group mt-3">
                        <input type="text" name="description" class="form-control" placeholder="Nhập tin nhắn...">
                        <button class="btn btn-primary" type="submit">Gửi</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection