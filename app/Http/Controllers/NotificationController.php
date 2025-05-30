<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
        // Controller NotificationController@index

    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get(); 
             return view('notifications.index', compact('notifications'));
    }



    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return redirect()->back()->with('success', 'Đã đánh dấu là đã đọc');
    }
}
