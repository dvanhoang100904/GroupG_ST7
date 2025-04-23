<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;

class SlideController extends Controller
{
    // Danh sách slide
    public function index()
    {
        $slides = Slide::latest()->paginate(4); // số lượng mỗi trang, ví dụ 10
        return view('admin.content.slide.list', compact('slides'));
    }


    // Hiển thị form tạo slide mới
    public function create()
    {
        return view('admin.content.slide.create');
    }

    // Lưu slide mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $slide = new Slide();
        $slide->name = $request->name;

        if ($request->hasFile('image')) {
            $slide->image = $request->file('image')->store('images/slides', 'public');
        }

        $slide->created_at = now(); // nếu bạn muốn tự gán, còn không thì Laravel tự làm
        $slide->save();

        return redirect()->route('slide.index')->with('success', 'Thêm slide thành công!');
    }


    // Xem chi tiết slide
    public function read($id)
    {
        $slide = Slide::findOrFail($id);
        return view('admin.content.slide.read', compact('slide'));
    }

    // Hiển thị form chỉnh sửa
    public function edit(Slide $slide)
    {
        return view('admin.content.slide.edit', compact('slide'));
    }


    // Cập nhật slide
    public function update(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slide->name = $request->name;

        // Nếu có ảnh mới thì xử lý
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/slides'), $imageName);
            $slide->image = 'uploads/slides/' . $imageName;
        }

        $slide->save();

        return redirect()->route('slide.index')->with('success', 'Cập nhật slide thành công.');
    }

    // Xóa slide
    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);

        // Xóa file ảnh nếu cần
        if (file_exists(public_path($slide->image))) {
            unlink(public_path($slide->image));
        }

        $slide->delete();

        return redirect()->route('slide.index')->with('success', 'Xóa slide thành công.');
    }
}
