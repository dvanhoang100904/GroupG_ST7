<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //Hiển thị user
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('user_id', 'asc')
                    ->paginate(8)
                    ->appends(['search' => $search]);

        return view('admin.content.user.list', compact('users', 'search'));
    }


    //Tạo user
    public function create()
    {
        $roles = Role::all();
        return view('admin.content.user.create', compact('roles'));
    }


    //User
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'role_id' => 'required|exists:roles,role_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = Str::slug($user->name) . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatars'), $avatarName);
            $user->avatar = "avatars/{$avatarName}";
        }

        $user->save();

        return redirect()->route('users.list')->with('success', 'Thêm người dùng thành công.');
    }

    //Detail user - fix lỗi nếu user không tồn tại
    public function read($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('users.list')->with('warning', 'Người dùng này không tồn tại hoặc đã bị xóa.');
        }

        return view('admin.content.user.read', compact('user'));
    }


    // Edit user (tương tự như kiểm tra địa chỉ giao hàng)
public function edit($userId)
{
    $user = User::find($userId);

    if (!$user) {
        // User không tồn tại hoặc đã bị xoá → quay về danh sách với message
        return redirect()->route('users.list')
            ->with('warning', 'Người dùng này không tồn tại hoặc đã bị xóa.');
    }

    return view('admin.content.user.edit', compact('user'));
}


// Update user - cũng phải kiểm tra user tồn tại trước khi update
public function update(Request $request, $userId)
{
    $user = User::find($userId);
    if ($user->updated_at != $request->input('updated_at')) {
        return back()->withErrors(['general' => 'Thông tin người dùng đã được cập nhật từ phiên khác. Vui lòng tải lại trang.']);
    }

    if (!$user) {
        // Nếu user đã bị xóa rồi, không update được nữa
        return redirect()->route('users.list')
            ->with('warning', 'Người dùng đã bị xóa hoặc không tồn tại, không thể cập nhật.');
    }

    $request->validate([
        'name' => 'required|string|max:50',
        'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->user_id . ',user_id',
        'role_id' => 'required|exists:roles,role_id',
        'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
        'password' => 'nullable|string|min:6',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif',
    ]);

    // Cập nhật thông tin
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->role_id = $request->role_id;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    if ($request->hasFile('avatar')) {
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }
        $avatar = $request->file('avatar');
        $avatarName = Str::slug($user->name) . '.' . $avatar->getClientOriginalExtension();
        $avatar->move(public_path('avatars'), $avatarName);
        $user->avatar = "avatars/{$avatarName}";
    }

    $user->save();

    return redirect()->route('users.read', $user->user_id)
        ->with('success', 'Cập nhật người dùng thành công.');
}
 
    //Xử lý xoá user
    public function destroy($id)
        {
            $user = User::find($id);

            if (!$user) {
                return redirect()->route('users.list')->with('warning', 'Người dùng này đã bị xóa hoặc không tồn tại!');
            }

            if ($user->user_id == 1) {
                return redirect()->route('users.list')->with('error', 'Không thể xoá người dùng Admin!');
            }

            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $user->delete();

            return redirect()->route('users.list')->with('success', 'Người dùng đã bị xóa.');
        }


}
