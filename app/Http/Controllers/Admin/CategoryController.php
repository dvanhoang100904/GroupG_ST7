<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục (có tìm kiếm)
    public function index(Request $request)
    {
        $query = Category::query();

        if ($search = $request->input('search')) {
            $query->where('category_name', 'like', "%$search%");
        }

        $perPage = 4;
        $page = $request->input('page', 1);

        $categories = $query->paginate($perPage);

        // Kiểm tra nếu truy cập page không hợp lệ
        if ($page > $categories->lastPage()) {
            return redirect()->route('category.index')->with('error', 'Trang không tồn tại.');
        }

        return view('admin.content.category.list', compact('categories'));
    }

    // Hiển thị form thêm danh mục
    public function create()
    {
        return view('admin.content.category.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->merge([
            'category_name' => strip_tags($request->category_name),
            'slug' => strip_tags($request->slug),
            'description' => strip_tags($request->description),
        ]);

        $request->validate([
            'category_name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                function ($attribute, $value, $fail) {
                    if (preg_match('/ {2,}/', $value)) {
                        $fail('Tên danh mục không được chứa 2 dấu cách liên tiếp.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (trim(preg_replace('/[\p{Z}\s　\xA0]/u', '', $value)) === '') {
                        $fail('Tên danh mục không được chỉ chứa khoảng trắng.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (preg_match('/[^a-zA-Z0-9\s]/', $value)) {
                        $fail('Tên danh mục không được chứa ký tự đặc biệt như @, #, !, v.v.');
                    }
                },
                'unique:categories,category_name',
            ],
            'slug' => [
                'nullable',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z0-9\-]+$/',
                'unique:categories,slug',
            ],
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'category_name.required' => 'Tên danh mục là bắt buộc.',
            'category_name.min' => 'Tên danh mục phải có ít nhất :min ký tự.',
            'category_name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
            'category_name.unique' => 'Tên danh mục đã tồn tại.',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.regex' => 'Slug chỉ được chứa chữ, số và dấu gạch ngang.',
        ]);


        $category = new Category();
        $category->category_name = $request->category_name;
        $category->slug = $request->slug ?? Str::slug($request->category_name);
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img_category'), $imageName);
            $category->image = 'img_category/' . $imageName;
        }

        $category->save();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    // Xóa danh mục
    public function destroy(Category $category)
    {
        // Nếu danh mục có ảnh -> xóa ảnh trong ổ cứng
        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->delete(); // Xóa trong DB

        return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa.');
    }

    // Xem chi tiết danh mục
    public function read(Category $category)
    {
        return view('admin.content.category.read', compact('category'));
    }

    // Hiển thị form sửa danh mục
    public function edit(Category $category)
    {
        return view('admin.content.category.edit', compact('category'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục không tồn tại.');
        }

        // Kiểm tra xung đột updated_at (Test case 1)
        if (
            $request->has('updated_at') &&
            //$request->input('updated_at') != $category->updated_at->toDateTimeString()
            $request->input('updated_at') !== $category->updated_at->format('Y-m-d H:i:s')
        ) {
            // Tự động reload dữ liệu trong form và thông báo
            return redirect()->route('category.edit', $id)->withInput()->with('warning', 'Trang đã được làm mới, vui lòng nhấn Lưu lại một lần nữa để cập nhật.');
        }

        $request->merge([
            'category_name' => strip_tags($request->category_name),
            'slug' => strip_tags($request->slug),
            'description' => strip_tags($request->description),
        ]);

        $request->validate([
            'category_name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9\s]+$/', // Không cho ký tự đặc biệt
                function ($attribute, $value, $fail) {
                    // Kiểm tra chuỗi toàn khoảng trắng (test case 3)
                    if (trim(preg_replace('/[\p{Z}\s　\xA0]/u', '', $value)) === '') {
                        $fail('Tên danh mục không được chỉ chứa khoảng trắng.');
                    }
                },
                'unique:categories,category_name,' . $category->category_id . ',category_id',
            ],
            'slug' => [
                'nullable',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z0-9\-]+$/', // Cho phép chữ, số, dấu gạch ngang
                'unique:categories,slug,' . $category->category_id . ',category_id',
            ],
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ], [
            'category_name.required' => 'Tên danh mục là bắt buộc.',
            'category_name.regex' => 'Tên danh mục chỉ được chứa chữ cái và số, không được chứa ký tự đặc biệt.',
            'slug.regex' => 'Slug chỉ được chứa chữ, số và dấu gạch ngang.',
        ]);

        $category->category_name = $request->category_name;
        $category->slug = $request->slug ?? Str::slug($request->category_name);
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img_category'), $imageName);
            $category->image = 'img_category/' . $imageName;
        }

        $category->save();

        return redirect()->route('category.read', $category->category_id)->with('success', 'Cập nhật danh mục thành công.');
    }
}
