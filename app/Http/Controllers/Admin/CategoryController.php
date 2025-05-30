<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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

        if ($page > $categories->lastPage()) {
            return redirect()->route('category.index')->with('error', 'Trang không tồn tại.');
        }

        return view('admin.content.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.content.category.create');
    }

    public function store(Request $request)
    {
        // anh  mac dinh
        $defaultImagePath = 'images/default/upload.png';
        // Strip tags
        $request->merge([
            'category_name' => strip_tags($request->category_name),
            'slug' => strip_tags($request->slug),
            'description' => strip_tags($request->description),
        ]);

        $request->validate(
            [
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
                        if (preg_match('/[^\p{L}\p{N}\s]/u', $value)) {
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
            ],
            [
                'category_name.required' => 'Tên danh mục là bắt buộc.',
                'category_name.min' => 'Tên danh mục phải có ít nhất :min ký tự.',
                'category_name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
                'category_name.unique' => 'Tên danh mục đã tồn tại.',
                'slug.unique' => 'Slug đã tồn tại.',
                'slug.regex' => 'Slug chỉ được chứa chữ, số và dấu gạch ngang.',
                'image.image' => 'Bạn phải chọn đúng định dạng hình ảnh (jpeg, png, jpg, gif, svg).',
                'image.mimes' => 'Ảnh chỉ được có định dạng: jpeg, png, jpg, gif, svg.',
                'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            ]
        );

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->slug = $request->slug ?? Str::slug($request->category_name);
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img_category'), $imageName);
            $category->image = 'img_category/' . $imageName;
        } else {
            $category->image = $defaultImagePath;
        }

        $category->save();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function destroy($id)
    {
        $defaultImagePath = 'images/default/upload.png';

        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục này không tồn tại hoặc đã bị xóa.');
        }

        if ($category->image && $category->image !== $defaultImagePath && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }


        $category->delete();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa.');
    }


    public function read($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục này không tồn tại hoặc đã bị xóa.');
        }

        return view('admin.content.category.read', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục này không tồn tại hoặc đã bị xóa.');
        }

        return view('admin.content.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // img mac dinh
        $defaultImagePath = 'images/default/upload.png';


        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục này không tồn tại hoặc đã bị xóa.');
        }

        // Check conflict (Test case 1)
        if (
            $request->has('updated_at') &&
            $request->input('updated_at') !== $category->updated_at->format('Y-m-d H:i:s')
        ) {
            return redirect()->route('category.edit', $id)
                ->withInput()
                ->with('warning', 'Trang đã được làm mới, vui lòng nhấn Lưu lại một lần nữa để cập nhật.');
        }

        $request->merge([
            'category_name' => strip_tags($request->category_name),
            'slug' => strip_tags($request->slug),
            'description' => strip_tags($request->description),
        ]);

        $request->validate(
            [
                'category_name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:30',
                    function ($attribute, $value, $fail) {
                        if (preg_match('/ {2,}/', $value)) {
                            $fail('Tên danh mục không được chứa 2 dấu cách liên tiếp.');
                        }
                        if (trim(preg_replace('/[\p{Z}\s　\xA0]/u', '', $value)) === '') {
                            $fail('Tên danh mục không được chỉ chứa khoảng trắng.');
                        }
                        if (preg_match('/[^\p{L}\p{N}\s]/u', $value)) {
                            $fail('Tên danh mục không được chứa ký tự đặc biệt như @, #, !, v.v.');
                        }
                    },
                    'unique:categories,category_name,' . $category->category_id . ',category_id',
                ],
                'slug' => [
                    'nullable',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[a-zA-Z0-9\-]+$/',
                    'unique:categories,slug,' . $category->category_id . ',category_id',
                ],
                'description' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'category_name.required' => 'Tên danh mục là bắt buộc.',
                'category_name.min' => 'Tên danh mục phải có ít nhất :min ký tự.',
                'category_name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
                'category_name.unique' => 'Tên danh mục đã tồn tại.',
                'slug.unique' => 'Slug đã tồn tại.',
                'slug.regex' => 'Slug chỉ được chứa chữ, số và dấu gạch ngang.',
                'image.image' => 'Bạn phải chọn đúng định dạng hình ảnh (jpeg, png, jpg, gif, svg).',
                'image.mimes' => 'Ảnh chỉ được có định dạng: jpeg, png, jpg, gif, svg.',
                'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            ]
        );

        $category->category_name = $request->category_name;
        $category->slug = $request->slug ?? Str::slug($request->category_name);
        $category->description = $request->description;


        if ($request->hasFile('image')) {
            if ($category->image && $category->image !== $defaultImagePath && File::exists(public_path($category->image))) {
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
