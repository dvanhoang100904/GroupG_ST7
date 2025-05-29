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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^(?!.* {2,})(?! )(?!.* $).+$/',
                'unique:slides,name' . (isset($slide) ? ',' . $slide->slide_id . ',slide_id' : ''),
            ],
            'image' => [
                $request->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'dimensions:min_height=300,max_height=700,max_width=1300',
            ],
        ], [
            'name.required' => 'Tên slide không được để trống.',
            'name.min' => 'Nhập sai – yêu cầu nhập ít nhất 3 ký tự.',
            'name.regex' => 'Nhập sai – không được có 2 khoảng trắng liên tiếp hoặc ở đầu/cuối.',
            'name.unique' => 'Tên slide đã tồn tại.',
            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận định dạng jpg, jpeg, png, gif.',
            'image.dimensions' => 'Ảnh phải cao 300–700px, ngang tối đa 1300px.',
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
        $slide = Slide::find($id);

        if (!$slide) {
            return redirect()->route('slide.index')->with('error', 'Slide không tồn tại hoặc đã bị xóa.');
        }

        return view('admin.content.slide.read', compact('slide'));
    }


    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $slide = Slide::find($id);

        if (!$slide) {
            return redirect()->route('slide.index')->with('error', 'Slide không tồn tại hoặc đã bị xóa.');
        }

        return view('admin.content.slide.edit', compact('slide'));
    }

    // Cập nhật slide
    public function update(Request $request, $id)
    {
        $slide = Slide::find($id);

        if (!$slide) {
            return redirect()->route('slide.index')->with('error', 'Slide không tồn tại hoặc đã bị xóa.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^(?!.* {2,})(?! )(?!.* $).+$/',
                'unique:slides,name' . (isset($slide) ? ',' . $slide->slide_id . ',slide_id' : ''),
            ],
            'image' => [
                $request->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'dimensions:min_height=300,max_height=700,max_width=1300',
            ],
        ], [
            'name.required' => 'Tên slide không được để trống.',
            'name.min' => 'Nhập sai – yêu cầu nhập ít nhất 3 ký tự.',
            'name.regex' => 'Nhập sai – không được có 2 khoảng trắng liên tiếp hoặc ở đầu/cuối.',
            'name.unique' => 'Tên slide đã tồn tại.',
            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận định dạng jpg, jpeg, png, gif.',
            'image.dimensions' => 'Ảnh phải cao 300–700px, ngang tối đa 1300px.',
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
        $slide = Slide::find($id);

        if (!$slide) {
            return redirect()->route('slide.index')->with('error', 'Slide không tồn tại hoặc đã bị xóa.');
        }

        $name = $slide->name;
        $slide->delete();

        return redirect()->route('slide.index')->with('success', "Đã xóa slide '{$name}' thành công.");
    }

    // chưc năng hiện thị 
    public function toggleVisibility($id)
    {
        $slide = Slide::find($id);

        if (!$slide) {
            return redirect()->route('slide.index')->with('error', 'Slide không tồn tại hoặc đã bị xóa.');
        }

        $slide->is_visible = !$slide->is_visible;
        $slide->save();

        $status = $slide->is_visible ? 'hiển thị' : 'ẩn';
        return redirect()->route('slide.index')->with('success', "Slide đã được chuyển sang trạng thái {$status}.");
    }
}
