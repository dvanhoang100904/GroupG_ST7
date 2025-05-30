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

    //Detail user
    public function read(User $user)
    {
        return view('admin.content.user.read', compact('user'));
    }


    //Edit user
    public function edit($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $roles = Role::all();
            return view('admin.content.user.edit', compact('user', 'roles'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.list')->with('warning', 'Người dùng không tồn tại hoặc đã bị xoá.');
        }
    }

    //Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->user_id . ',user_id',
            'role_id' => 'required|exists:roles,role_id',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ]);

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

        return redirect()->route('users.read', $user->user_id)->with('success', 'Cập nhật người dùng thành công.');
    }

    //Xử lý xoá user
    public function destroy(User $user)
        {
            if ($user->user_id == 1) {
                    return redirect()->route('users.list')->with('error', 'Không thể xoá người dùng Admin!');
                }
            try {
                 
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    unlink(public_path($user->avatar));
                }

                $user->delete();

                return redirect()->route('users.list')->with('success', 'Người dùng đã bị xóa.');
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return redirect()->route('users.list')->with('warning', 'Người dùng này đã bị xóa hoặc không tồn tại!');
            } catch (\Exception $e) {
                return redirect()->route('users.list')->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại.');
            }
        }

}
