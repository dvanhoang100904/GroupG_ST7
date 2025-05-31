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
        $selectedUserId = $request->input('user_id');

        if ($selectedUserId && !User::find($selectedUserId)) {
            return redirect()->route('admin.chat.index')->with('error', 'Người dùng không tồn tại.');
        }

        $query = Chat::with('user')->whereNull('assessment_star_id');

        if ($selectedUserId) {
            $query->where(function ($q) use ($selectedUserId) {
                $q->where('user_id', $selectedUserId)
                    ->orWhere('receiver_id', $selectedUserId);
            });
        }

        $chats = $query->orderBy('created_at')->paginate(30)->withQueryString();

        $users = User::whereHas('chats', function ($q) {
            $q->where('receiver_id', auth()->id())
                ->orWhere('user_id', auth()->id());
        })->get();

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
            'receiver_id' => 1, // TODO: Cần làm động nếu nhiều admin
            'description' => $request->message,
            'type' => 'chat',
            'status' => 'open',
        ]);

        return back()->with('success', 'Đã gửi tin nhắn');
    }

    public function detail($id)
    {
        $adminId = auth()->id();

        try {
            $chats = Chat::with('user')
                ->where('receiver_id', $adminId)

                ->orderBy('created_at')
                ->get()
                ->groupBy('user_id');

            $grouped = $chats->map(function ($items, $userId) {
                return [
                    'user_id' => $userId,
                    'user_name' => optional($items->first()->user)->name ?? 'Không rõ',
                    'messages' => $items->map(function ($chat) {
                        return [
                            'description' => $chat->description,
                            'created_at' => $chat->created_at->format('d/m/Y H:i')
                        ];
                    })
                ];
            });

            $users = User::whereHas('chats', function ($q) use ($adminId) {
                $q->where('receiver_id', $adminId)->orWhere('user_id', $adminId);
            })->where('user_id', '!=', 1)->get();

            return response()->json([
                'groupedChats' => $grouped,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Dữ liệu không hợp lệ, vui lòng tải lại trang.'], 400);
        }
    }

    public function showChat($id)
    {
        $adminId = auth()->id();

        try {
            $user = User::find($id);
            if (!$user) {
                return redirect()->route('admin.chat.index')->with('error', 'Người dùng không tồn tại.');
            }

            $chats = Chat::with('user')
                ->where(function ($q) use ($id, $adminId) {
                    $q->where('user_id', $id)->where('receiver_id', $adminId);
                })
                ->orWhere(function ($q) use ($id, $adminId) {
                    $q->where('user_id', $adminId)->where('receiver_id', $id);
                })
                ->orderBy('created_at')
                ->get();

            $users = User::whereHas('chats', function ($q) use ($adminId) {
                $q->where('receiver_id', $adminId)->orWhere('user_id', $adminId);
            })->where('user_id', '!=', 1)->get();

            $selectedUserId = $id;

            return view('admin.content.website.chat', compact('user', 'chats', 'users', 'selectedUserId'))
                ->with([
                    'editingChatId' => session('editing_chat_id'),
                    'editingChatContent' => session('editing_chat_content'),
                ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.chat.index')->with('error', 'Người dùng không tồn tại hoặc tin nhắn đã bị xóa.');
        }
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:1000'
        ]);

        if (!User::find($id)) {
            return redirect()->route('admin.chat.index')->with('error', 'Người dùng không tồn tại.');
        }

        $chat = new Chat();
        $chat->user_id = Auth::id();
        $chat->receiver_id = $id;
        $chat->description = $request->description;
        $chat->type = 'reply';
        $chat->status = 'open';
        $chat->assessment_star_id = null;
        $chat->photo = null;
        $chat->save();

        return redirect()->route('admin.chat.show', ['id' => $id])
            ->with('success', 'Phản hồi đã được gửi thành công.');
    }
    public function edit(Chat $chat)
    {
        if (!$chat || !$chat->exists) {
            return redirect()->route('admin.chat.show', ['id' => auth()->id()])
                ->with('error', 'Tin nhắn không tồn tại hoặc đã bị xóa.');
        }

        // Kiểm tra quyền và thời gian chỉnh sửa
        if ($chat->created_at->diffInMinutes(now()) > 5 || $chat->user_id !== auth()->id()) {
            return redirect()->route('admin.chat.show', [
                'id' => $chat->user_id === auth()->id() ? $chat->receiver_id : $chat->user_id
            ])->with('error', 'Không thể sửa tin nhắn này.');
        }

        // Kiểm tra xem tin nhắn có bị thay đổi so với session hay không
        if (session('editing_chat_id') && session('editing_chat_id') == $chat->chat_id) {
            if (session('editing_chat_content') !== $chat->description) {
                session()->forget(['editing_chat_id', 'editing_chat_content']);
                return redirect()->route('admin.chat.show', [
                    'id' => $chat->user_id === auth()->id() ? $chat->receiver_id : $chat->user_id
                ])->with('warning', 'Tin nhắn đã bị thay đổi, vui lòng kiểm tra lại.');
            }
        }

        // Lưu session sửa
        session([
            'editing_chat_id' => $chat->chat_id,
            'editing_chat_content' => $chat->description,
        ]);

        return redirect()->route('admin.chat.show', [
            'id' => $chat->user_id === auth()->id() ? $chat->receiver_id : $chat->user_id
        ]);
    }


    public function update(Request $request, Chat $chat)
    {
        if (!$chat || !$chat->exists) {
            return redirect()->route('admin.chat.show', ['id' => auth()->id()])
                ->with('error', 'Tin nhắn không tồn tại hoặc đã bị xóa.');
        }

        if ($chat->created_at->diffInMinutes(now()) > 5 || $chat->user_id !== auth()->id()) {
            return redirect()->route('admin.chat.show', [
                'id' => $chat->user_id === auth()->id() ? $chat->receiver_id : $chat->user_id
            ])->with('error', 'Không thể sửa tin nhắn này.');
        }

        $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        if ($chat->description === $request->description) {
            return redirect()->route('admin.chat.show', [
                'id' => $chat->user_id === auth()->id() ? $chat->receiver_id : $chat->user_id
            ])->with('info', 'Không có thay đổi nào được thực hiện.');
        }

        $chat->description = $request->description;
        $chat->save();

        session()->forget(['editing_chat_id', 'editing_chat_content']);

        return redirect()->route('admin.chat.show', [
            'id' => $chat->user_id === auth()->id() ? $chat->receiver_id : $chat->user_id
        ])->with('success', 'Cập nhật tin nhắn thành công!');
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
