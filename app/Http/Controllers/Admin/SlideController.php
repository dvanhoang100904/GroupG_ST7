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
        $slides = Slide::oldest()->paginate(2);

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
            'image' => 'required|image|mimes:jpg,jpeg,png,gif', //|max:2048 neu muốn giới hạn
        ]);

        $slide = new Slide();
        $slide->name = $request->name;

        if ($request->hasFile('image')) {
            $slide->image = $request->file('image')->store('images/slides', 'public');
        }

        $slide->created_at = now();
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $slide->name = $request->name;

        if ($request->hasFile('image')) {
            if ($slide->image) {
                \Storage::disk('public')->delete($slide->image);
            }

            $slide->image = $request->file('image')->store('images/slides', 'public');
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
