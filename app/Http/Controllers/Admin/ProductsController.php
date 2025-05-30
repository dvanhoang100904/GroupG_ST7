<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm với tìm kiếm và phân trang
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ query string (?search=...)

        $products = Product::when($search, function ($query, $search) {
            return $query->where('product_name', 'like', "%{$search}%");
        })
            ->orderBy('product_id', 'asc')
            ->paginate(2); // Phân trang: 2 sản phẩm mỗi trang

        $products->appends(['search' => $search]); // Giữ nguyên tham số search khi chuyển trang

        return view('admin.content.products.list', compact('products', 'search')); // Trả về view danh sách
    }

    /**
     * Hiển thị form tạo mới sản phẩm
     */
    public function create()
    {
        $categories = Category::all(); // Lấy tất cả danh mục sản phẩm
        return view('admin.content.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'product_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|alpha_dash|unique:products,slug',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|integer|exists:categories,category_id',
        ]);

        // Tạo instance mới
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        // Xử lý slug, tránh trùng lặp
        $slug = $request->slug ?: Str::slug($request->product_name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $product->slug = $slug;

        // Xác định đường dẫn ảnh theo danh mục
        $category = Category::find($request->category_id);
        $categorySlug = Str::slug($category->category_name);

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = $slug . '.' . $extension;
            $image->move(public_path("images/{$categorySlug}"), $imageName);
            $product->image = "images/{$categorySlug}/{$imageName}";
        } else {
            // Nếu không có ảnh, dùng ảnh mặc định
            $product->image = "images/{$categorySlug}/mac-dinh.jpg";
        }

        $product->save(); // Lưu vào CSDL

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function read($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return redirect()->route('products.list')
                ->with('error', 'Hiện tại sản phẩm không tồn tại');
        }

        return view('admin.content.products.read', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::all();

        if (!$product) {
            return redirect()->route('products.list')
                ->with('error', 'Hiện tại sản phẩm không tồn tại');
        }

        return view('admin.content.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.list')
                ->with('error', 'Hiện tại sản phẩm không tồn tại');
        }

        // Xác thực dữ liệu
        $request->validate([
            'product_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        // Slug duy nhất
        $slug = $request->slug ?: Str::slug($request->product_name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('product_id', '!=', $product->product_id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $product->slug = $slug;

        $category = Category::find($request->category_id);
        $categorySlug = Str::slug($category->category_name);

        // Cập nhật ảnh nếu có file ảnh mới
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $extension = strtolower($image->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                return back()->withErrors(['image' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif']);
            }

            $imageName = $slug . '.' . $extension;
            $image->move(public_path("images/{$categorySlug}"), $imageName);
            $product->image = "images/{$categorySlug}/{$imageName}";
        }

        $product->save();

        return redirect()->route('products.read', $product->product_id)->with('success', 'Cập nhật sản phẩm thành công.');
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.list')
                ->with('error', 'Hiện tại sản phẩm không tồn tại');
        }

        // Xóa ảnh nếu có và tồn tại file ảnh
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được xóa.');
    }
}
