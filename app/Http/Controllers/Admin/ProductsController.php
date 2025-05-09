<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;  // Thêm để sử dụng phương thức slug

class ProductsController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm với tùy chọn tìm kiếm.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::when($search, function ($query, $search) {
            return $query->where('product_name', 'like', "%{$search}%");
        })
        ->orderBy('product_id', 'asc')
        ->paginate(2);

        $products->appends(['search' => $search]);

        return view('admin.content.products.list', compact('products', 'search'));
    }

    /** 
     * Hiển thị form tạo mới sản phẩm.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.content.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'category_id' => 'required|integer|exists:categories,category_id',
        ]);

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        // Lấy slug danh mục
        $category = Category::find($request->category_id);
        $categorySlug = Str::slug($category->category_name);
        $slug = Str::slug($request->product_name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $slug . '.' . $image->getClientOriginalExtension();
            // Lưu vào thư mục images/{slug} tương ứng với danh mục
            $image->move(public_path("images/{$categorySlug}"), $imageName);
            $product->image = "images/{$categorySlug}/{$imageName}";
        } else {
            // Ảnh mặc định theo danh mục: {categorySlug}/mac-dinh.jpg
            $product->image = "images/{$categorySlug}/mac-dinh.jpg";
        }

        $product->save();

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    /**
     * Hiển thị chi tiết sản phẩm.
     */
    public function read(Product $product)
    {
        $product->load('category');
        return view('admin.content.products.read', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.content.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật thông tin sản phẩm.
     */
    public function update(Request $request, Product $product)
    {
        // Xác thực dữ liệu
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'category_id' => 'required|integer|exists:categories,category_id',
        ]);

        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        $category = Category::find($request->category_id);
        $categorySlug = Str::slug($category->category_name);
        $slug = Str::slug($request->product_name);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = $slug . '.' . $image->getClientOriginalExtension();
            // Lưu ảnh mới vào thư mục {categorySlug}
            $image->move(public_path("images/{$categorySlug}"), $imageName);
            $product->image = "images/{$categorySlug}/{$imageName}";
        }

        $product->save();

        return redirect()->route('products.read', $product->product_id)->with('success', 'Cập nhật sản phẩm thành công.');
    }

    /**
     * Xóa sản phẩm khỏi cơ sở dữ liệu.
     */
    public function destroy(Product $product)
    {
        // Xóa ảnh nếu có
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được xóa.');
    }
}
