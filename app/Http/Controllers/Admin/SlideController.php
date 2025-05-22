<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;

class SlideController extends Controller
{
    // Danh sách slide
    public function index(Request $request)
    {
        // Lấy giá trị tìm kiếm từ request
        $search = $request->input('search');

        // Lọc theo tên slide nếu có tìm kiếm
        $slides = Slide::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->oldest()->paginate(2);

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
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img_slide'), $filename);
            $slide->image = 'img_slide/' . $filename;
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
            if ($slide->image && file_exists(public_path($slide->image))) {
                unlink(public_path($slide->image));
            }
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img_slide'), $filename);
            $slide->image = 'img_slide/' . $filename;
        }


        $slide->save();

        return redirect()->route('slide.read', $slide->slide_id)->with('success', 'Cập nhật slide thành công.');
    }

    // Xóa slide
    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);

        // Xóa file ảnh nếu cần
        if ($slide->image && file_exists(public_path($slide->image))) {
            unlink(public_path($slide->image));
        }

        $slide->delete();

        return redirect()->route('slide.index')->with('success', 'Xóa slide thành công.');
    }


    // chưc năng hiện thị 
    public function toggleVisibility($id)
    {
        $slide = Slide::findOrFail($id);

        if (!$slide->is_visible) {
            // Nếu đang ẩn và được nhấn để hiện, thì ẩn tất cả slide khác
            Slide::where('slide_id', '!=', $id)->update(['is_visible' => false]);
        }

        // Toggle trạng thái
        $slide->is_visible = !$slide->is_visible;
        $slide->save();

        return redirect()->route('slide.index')->with('success', 'Cập nhật trạng thái hiển thị slide thành công.');
    }
}
