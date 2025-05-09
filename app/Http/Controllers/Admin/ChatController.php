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
    
        return view('admin.content.website.chat', compact('chats', 'users', 'selectedUserId'));
    }
    
    // public function reply(Request $request, $chatId)
    // {
    //     $request->validate([
    //         'reply_content' => 'required|string',
    //     ]);

    //     $originalChat = Chat::findOrFail($chatId);

    //     $reply = new Chat();
    //     $reply->description = $request->reply_content;
    //     $reply->user_id = Auth::id(); // admin
    //     $reply->receiver_id = $originalChat->user_id;
    //     $reply->type = 'reply';
    //     $reply->assessment_star_id = $originalChat->assessment_star_id;
    //     $reply->save();

    //     return response()->json(['message' => 'Trả lời thành công']);
    // }
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
    // public function detail($id)
    // {
    //     $user = User::findOrFail($id);

    //     $messages = Chat::where(function ($q) use ($id) {
    //             $q->where('user_id', $id)->where('receiver_id', auth()->id());
    //         })
    //         ->orWhere(function ($q) use ($id) {
    //             $q->where('user_id', auth()->id())->where('receiver_id', $id);
    //         })
    //         ->orderBy('created_at')
    //         ->get()
    //         ->map(function ($chat) {
    //             return [
    //                 'description' => $chat->description,
    //                 'time' => $chat->created_at->format('d/m/Y H:i'),
    //                 'sender_id' => $chat->user_id
    //             ];
    //         });

    //     return response()->json([
    //         'user' => $user->name,
    //         'current_user_id' => auth()->id(),
    //         'messages' => $messages
    //     ]);
    // }
    // Lấy thông tin chi tiết chat theo user
    public function detail($id)
    {
        $adminId = auth()->id();

        $chats = Chat::with('user')
            ->where('receiver_id', $adminId)
            ->orderBy('created_at')
            ->get()
            ->groupBy('user_id');

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

        return response()->json($grouped);
    }

    // Hiển thị trang chi tiết chat của một user
    public function showChat($id)
    {
        $user = User::findOrFail($id);
    
        // Lấy các tin nhắn với người dùng
        $chats = Chat::with('user')
            ->where(function ($q) use ($id) {
                $q->where('user_id', $id)->where('receiver_id', auth()->id());
            })
            ->orWhere(function ($q) use ($id) {
                $q->where('user_id', auth()->id())->where('receiver_id', $id);
            })
            ->orderBy('created_at')
            ->get();
        
        // Lấy danh sách người dùng đã từng chat
        $users = User::whereHas('chats', function ($q) {
            $q->where('receiver_id', auth()->id())
              ->orWhere('user_id', auth()->id());
        })->get();
        
        $selectedUserId = $id; // Dùng biến id để xác định người đang chat
        return view('admin.content.website.chat', compact('user', 'chats', 'users', 'selectedUserId'));
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
    
        // ✅ Sau khi gửi tin nhắn, chuyển về lại trang chat với người dùng
        return redirect()->route('admin.chat.show', ['id' => $id])
                         ->with('success', 'Phản hồi đã được gửi thành công.');
    }
}
