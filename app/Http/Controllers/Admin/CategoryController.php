<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm nếu có
        $search = $request->input('search');

        // Query danh sách danh mục có tìm kiếm + phân trang 20 dòng
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('category_id', 'desc')
            ->paginate(7);

        // Giữ nguyên từ khóa khi chuyển trang
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
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store('images/categories', 'public');
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    // XÓa
    public function destroy(Category $category)
    {
        // Xóa ảnh nếu có (tùy chọn)
        if ($category->image) {
            \Storage::disk('public')->delete($category->image); // vẫn chạy được nha!
        }

        $category->delete();

        return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa.');
    }


    // chi tiết
    public function read(Category $category)
    {
        return view('admin.content.category.read', compact('category'));
    }

    // sửa 
    // Hiển thị form sửa
    public function edit(Category $category)
    {
        return view('admin.content.category.edit', compact('category'));
    }

    // Lưu cập nhật
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $category->category_id . ',category_id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ]);

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }

            // Lưu ảnh mới
            $category->image = $request->file('image')->store('images/categories', 'public');
        }

        $category->save();

        return redirect()->route('admin.category.read', $category->category_id)->with('success', 'Cập nhật danh mục thành công.');
    }
}
