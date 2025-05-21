<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    // Hiển thị danh sách sản phẩm có phân trang và tìm kiếm
    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ query string (?search=...)

        $products = Product::when($search, function ($query, $search) {
            return $query->where('product_name', 'like', "%{$search}%"); // Tìm kiếm theo tên sản phẩm
        })->orderBy('product_id', 'asc')->paginate(2); // Sắp xếp theo ID và phân trang 2 sản phẩm/trang

        $products->appends(['search' => $search]); // Gắn lại tham số search vào pagination

        return view('admin.content.products.list', compact('products', 'search')); // Trả về view danh sách sản phẩm
    }

    // Hiển thị form tạo mới sản phẩm
    public function create()
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('admin.content.products.create', compact('categories')); // Trả về view với danh mục
    }

    // Lưu sản phẩm mới vào cơ sở dữ liệu
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

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        // Xử lý slug sản phẩm
        $slug = $request->slug ?: Str::slug($request->product_name);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $product->slug = $slug;

        // Tạo slug danh mục để dùng trong đường dẫn ảnh
        $category = Category::find($request->category_id);
        $categorySlug = Str::slug($category->category_name);

        // Nếu có upload ảnh
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // lấy đúng extension gốc
            $imageName = $slug . '.' . $extension; // Đặt tên theo slug + extension
            $image->move(public_path("images/{$categorySlug}"), $imageName);
            $product->image = "images/{$categorySlug}/{$imageName}";
        } else {
            // Nếu không có ảnh thì dùng ảnh mặc định
            $product->image = "images/{$categorySlug}/mac-dinh.jpg";
        }

        $product->save();

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    // Hiển thị chi tiết sản phẩm
    public function read(Product $product)
    {
        $product->load('category'); // Load thêm quan hệ danh mục
        return view('admin.content.products.read', compact('product'));
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all(); // Lấy danh sách danh mục để chọn
        return view('admin.content.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        $slug = $request->slug ?: Str::slug($request->product_name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('product_id', '!=', $product->product_id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $product->slug = $slug;

        $category = Category::find($request->category_id);
        $categorySlug = Str::slug($category->category_name);

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



    // Xóa sản phẩm
    public function destroy(Product $product)
    {

        $product->delete(); // Xóa sản phẩm khỏi cơ sở dữ liệu

        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được xóa.');
    }
}
