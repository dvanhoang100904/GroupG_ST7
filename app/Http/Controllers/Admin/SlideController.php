<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use Illuminate\Validation\Rule;

class SlideController extends Controller
{
    // Danh sách slide
    public function index(Request $request)
    {
        $search = $request->input('search');

        $slides = Slide::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->oldest()->paginate(1);

        // Kiểm tra nếu số trang vượt quá số trang hiện có
        if ($slides->lastPage() < $request->get('page', 1)) {
            return redirect()->route('slide.index')->with('error', 'Trang này không tồn tại hoặc đã bị xóa.');
        }

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
                function ($attribute, $value, $fail) {
                    if (preg_match('/ {2,}/', $value)) {
                        $fail('Tên slide không được chứa 2 khoảng trắng liên tiếp.');
                    }
                    if (trim(preg_replace('/[\p{Z}\s　\xA0]/u', '', $value)) === '') {
                        $fail('Tên slide không được chỉ chứa khoảng trắng.');
                    }
                    if (preg_match('/[^\p{L}\p{N}\s]/u', $value)) {
                        $fail('Tên slide không được chứa ký tự đặc biệt như @, #, !, v.v.');
                    }
                },
                'unique:slides,name',
            ],

            'image' => [
                $request->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'dimensions:min_height=300,max_height=700,max_width=1300',
            ],
        ], [
            'name.required' => 'Tên slide không được để trống.',
            'name.min' => 'Tên slide phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên slide không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên slide đã tồn tại.',

            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận định dạng jpg, jpeg, png, gif.',
            'image.dimensions' => 'Ảnh phải cao từ 300–700px, ngang tối đa 1300px.',
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
                function ($attribute, $value, $fail) {
                    if (preg_match('/ {2,}/', $value)) {
                        $fail('Tên slide không được chứa 2 khoảng trắng liên tiếp.');
                    }
                    if (trim(preg_replace('/[\p{Z}\s　\xA0]/u', '', $value)) === '') {
                        $fail('Tên slide không được chỉ chứa khoảng trắng.');
                    }
                    if (preg_match('/[^\p{L}\p{N}\s]/u', $value)) {
                        $fail('Tên slide không được chứa ký tự đặc biệt như @, #, !, v.v.');
                    }
                },
                Rule::unique('slides', 'name')->ignore($slide->slide_id, 'slide_id'),
            ],

            'image' => [
                $request->hasFile('image') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'dimensions:min_height=300,max_height=700,max_width=1300',
            ],
        ], [
            'name.required' => 'Tên slide không được để trống.',
            'name.min' => 'Tên slide phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên slide không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên slide đã tồn tại.',

            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận định dạng jpg, jpeg, png, gif.',
            'image.dimensions' => 'Ảnh phải cao từ 300–700px, ngang tối đa 1300px.',
        ]);

        $slide->name = $request->name;

        if ($request->hasFile('image')) {
            if ($slide->image && file_exists(public_path($slide->image)) && $slide->image !== 'img_slide/default.png') {
                unlink(public_path($slide->image));
            }

            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img_slide'), $filename);
            $slide->image = 'img_slide/' . $filename;
        }

        $slide->updated_at = now();
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

        // Kiểm tra nếu ảnh thật sự không tồn tại trên hệ thống (bị xóa ngoài thư mục)
        $imagePath = public_path($slide->image);
        if (!file_exists($imagePath)) {
            return redirect()->route('slide.index')->with('error', 'Hiện tại slide này không có ảnh.');
        }

        // Xử lý chuyển trạng thái
        if (!$slide->is_visible) {
            // Nếu đang bị ẩn → bật hiển thị và ẩn các slide khác
            Slide::where('slide_id', '!=', $slide->slide_id)->update(['is_visible' => false]);
            $slide->is_visible = true;
        } else {
            // Nếu đang hiển thị → ẩn nó đi
            $slide->is_visible = false;
        }

        $slide->save();

        $status = $slide->is_visible ? 'hiển thị' : 'ẩn';
        return redirect()->route('slide.index')->with('success', "Slide đã được chuyển sang trạng thái {$status}.");
    }
}
