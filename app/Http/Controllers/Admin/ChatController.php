<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $selectedUserId = $request->input('user_id'); // truyền vào nếu muốn xem chat với người dùng nào

        $query = Chat::with('user')->whereNull('assessment_star_id');

        if ($selectedUserId) {
            $query->where(function ($q) use ($selectedUserId) {
                $q->where('user_id', $selectedUserId)
                    ->orWhere('receiver_id', $selectedUserId);
            });
        }

        $chats = $query->orderBy('created_at')->get();

        $users = User::whereHas('chats', function ($q) {
            $q->where('receiver_id', auth()->id())
                ->orWhere('user_id', auth()->id());
        })->get();

        // Lấy thông tin edit từ session
        $editingChatId = session('editing_chat_id');
        $editingChatContent = session('editing_chat_content');

        return view('admin.content.website.chat', compact('chats', 'users', 'selectedUserId', 'editingChatId', 'editingChatContent'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Chat::create([
            'user_id' => auth()->id(),
            'receiver_id' => 1, // ID của admin hoặc user mặc định nhận tin nhắn
            'description' => $request->message,
            'type' => 'chat',
            'status' => 'open',
        ]);

        return back()->with('success', 'Đã gửi tin nhắn');
    }

    public function detail($id)
    {
        $adminId = auth()->id();

        // Lấy tất cả chat gửi đến admin, nhóm theo user gửi
        $chats = Chat::with('user')
            ->where('receiver_id', $adminId)
            ->orderBy('created_at')
            ->get()
            ->groupBy('user_id');

        // Gom nhóm chat thành mảng theo user
        $grouped = $chats->map(function ($items, $userId) {
            return [
                'user_id' => $userId,
                'user_name' => $items->first()->user->name ?? 'Không rõ',
                'messages' => $items->map(function ($chat) {
                    return [
                        'description' => $chat->description,
                        'created_at' => $chat->created_at->format('d/m/Y H:i')
                    ];
                })
            ];
        });

        // Lấy danh sách người dùng đã chat với admin, loại trừ user_id = 1 (admin)
        $users = User::whereHas('chats', function ($q) use ($adminId) {
            $q->where('receiver_id', $adminId)
                ->orWhere('user_id', $adminId);
        })->where('user_id', '!=', 1)->get();

        return response()->json([
            'groupedChats' => $grouped,
            'users' => $users,
        ]);
    }

    // Hiển thị trang chi tiết chat của một user
    public function showChat($id)
    {
        $adminId = auth()->id();

        $user = User::findOrFail($id);

        // Lấy các tin nhắn 2 chiều giữa user này và admin
        $chats = Chat::with('user')
            ->where(function ($q) use ($id, $adminId) {
                $q->where('user_id', $id)->where('receiver_id', $adminId);
            })
            ->orWhere(function ($q) use ($id, $adminId) {
                $q->where('user_id', $adminId)->where('receiver_id', $id);
            })
            ->orderBy('created_at')
            ->get();

        // Lấy danh sách người dùng đã từng chat, loại trừ admin (user_id=1)
        $users = User::whereHas('chats', function ($q) use ($adminId) {
            $q->where('receiver_id', $adminId)
                ->orWhere('user_id', $adminId);
        })->where('user_id', '!=', 1)->get();

        $selectedUserId = $id; // Người đang chat

        return view('admin.content.website.chat', compact(
            'user',
            'chats',
            'users',
            'selectedUserId'
        ))->with([
            'editingChatId' => session('editing_chat_id'),
            'editingChatContent' => session('editing_chat_content'),
        ]);
    }

    // Gửi phản hồi từ admin đến người dùng
    public function reply(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'description' => 'required|string|max:1000'
        ]);

        // Tạo tin nhắn mới
        $chat = new Chat();
        $chat->user_id = Auth::id(); // Admin gửi
        $chat->receiver_id = $id;    // Người dùng nhận
        $chat->description = $request->description;
        $chat->type = 'reply';
        $chat->status = 'open';
        $chat->assessment_star_id = null;
        $chat->photo = null;
        $chat->save();

        //  Sau khi gửi tin nhắn, chuyển về lại trang chat với người dùng
        return redirect()->route('admin.chat.show', ['id' => $id])
            ->with('success', 'Phản hồi đã được gửi thành công.');
    }
    public function edit(Chat $chat)
    {
        if ($chat->created_at->diffInMinutes(now()) > 5 || $chat->user_id !== auth()->id()) {
            return back()->with('error', 'Không thể sửa tin nhắn này.');
        }

        session([
            'editing_chat_id' => $chat->chat_id,
            'editing_chat_content' => $chat->description,
        ]);

        return back();
    }

    public function update(Request $request, Chat $chat)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $chat->description = $request->description;
        $chat->save();
        session()->forget(['editing_chat_id', 'editing_chat_content']);
        return redirect()->back()->with('success', 'Cập nhật tin nhắn thành công!');
    }

    public function destroy(Chat $chat)
    {
        // Chỉ admin hoặc người tạo mới có quyền xóa
        if ($chat->user_id !== auth()->id()) {
            return back()->with('error', 'Bạn không có quyền xóa tin nhắn này.');
        }

        $chat->delete();

        return back();
    }
}
