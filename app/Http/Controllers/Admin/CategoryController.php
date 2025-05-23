<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục (có tìm kiếm)
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::when($search, function ($query, $search) {
            return $query->where('category_name', 'like', "%{$search}%");
        })
            ->orderBy('category_id', 'asc')
            ->paginate(4);

        $categories->appends(['search' => $search]);

        return view('admin.content.category.list', compact('categories', 'search'));
    }

    // Hiển thị form thêm danh mục
    public function create()
    {
        return view('admin.content.category.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ]);

        $category = new Category();
        $category->category_name = $request->category_name;

        // Tạo slug nếu chưa có
        $slug = $request->slug ?? Str::slug($request->category_name);
        $category->slug = $slug;

        $category->description = $request->description;

        // Nếu người dùng có upload ảnh, lưu vào storage như cũ
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
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name,' . $category->category_id . ',category_id',
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $category->category_id . ',category_id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ]);

        $category->category_name = $request->category_name;

        // Cập nhật lại slug nếu có thay đổi
        $slug = $request->slug ?? Str::slug($request->category_name);
        $category->slug = $slug;

        $category->description = $request->description;

        // Tạo lại thư mục nếu chưa có (trường hợp slug mới)
        $folderPath = public_path('images/' . $slug);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        // Nếu có ảnh mới upload
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
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
