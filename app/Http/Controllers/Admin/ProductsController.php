<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\PaginatorInvalidPageException;

class ProductsController extends Controller
{

    /**
     * Chuẩn hóa chuỗi: trim + thay nhiều khoảng trắng thành 1 khoảng trắng
     */
    protected function cleanText(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }
        // trim + replace nhiều khoảng trắng (Unicode safe) thành 1 dấu cách
        return preg_replace('/\s+/u', ' ', trim($text));
    }

    /**
     * Hiển thị danh sách sản phẩm với tìm kiếm và phân trang
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page');

        // 👉 Kiểm tra nếu page không phải số nguyên dương (vd: abc, -1, v.v.)
        if ($page && (!ctype_digit($page) || (int)$page < 1)) {
            return redirect()->route('products.list')->with('error', 'Tham số phân trang không hợp lệ.');
        }

        $products = Product::when($search, function ($query, $search) {
            return $query->where('product_name', 'like', "%{$search}%");
        })->orderBy('product_id', 'asc')->paginate(2)->appends(['search' => $search]);

        // 👉 Nếu trang yêu cầu vượt quá số trang hiện có
        if ($products->isEmpty() && $products->currentPage() > 1) {
            return redirect()->route('products.list')->with('error', 'Trang bạn yêu cầu vượt quá số trang hiện có.');
        }

        return view('admin.content.products.list', compact('products', 'search'));
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

        // Chuẩn hóa dữ liệu đầu vào
        $request->merge([
            'product_name' => $this->cleanText($request->product_name),
            'description' => $this->cleanText($request->description),
        ]);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'product_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|alpha_dash|unique:products,slug',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|integer|exists:categories,category_id',
        ], [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            
            'slug.alpha_dash' => 'Slug chỉ được chứa chữ cái, số, dấu gạch ngang hoặc gạch dưới.',
            'slug.unique' => 'Slug đã tồn tại. Vui lòng chọn slug khác.',
            
             'description.max' => 'Mô tả sản phẩm không được vượt quá 1000 ký tự.',

            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',
            
            'image.image' => 'Tệp tải lên phải là ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng: jpg, jpeg, png hoặc gif.',
            'image.max' => 'Dung lượng ảnh không được vượt quá 2MB.',

            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'category_id.exists' => 'Danh mục không tồn tại.',
        ]);

        // Kiểm tra chuỗi chỉ chứa khoảng trắng (kể cả khoảng trắng Unicode)
        if (preg_match('/^\s*$/u', $request->product_name)) {
            return back()->withErrors(['product_name' => 'Tên sản phẩm không được chỉ chứa khoảng trắng.'])->withInput();
        }
        if ($request->description !== null && preg_match('/^\s*$/u', $request->description)) {
            return back()->withErrors(['description' => 'Mô tả sản phẩm không được chỉ chứa khoảng trắng.'])->withInput();
        }

        // Kiểm tra trùng tên sản phẩm
        $existing = Product::where('product_name', $request->product_name)->first();
        if ($existing) {
            return back()->withErrors(['product_name' => 'Tên sản phẩm đã tồn tại.'])->withInput();
        }

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
            return Redirect::back()->with('not_found', 'Không tìm thấy sản phẩm.');
        }

        return view('admin.content.products.read', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return Redirect::back()->with('not_found', 'Không tìm thấy sản phẩm.');
        }

        $categories = Category::all();
        return view('admin.content.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, Product $product)
    {

        $request->merge([
            'product_name' => $this->cleanText($request->product_name),
            'description' => $this->cleanText($request->description),
        ]);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,category_id',
            'version' => 'required|integer', // Kiểm tra version
        ]);

        // Kiểm tra xung đột phiên bản (cập nhật từ tab cũ)
        if ($request->version != $product->version) {
            // Trả về với session version_conflict để view xử lý alert + redirect
            return redirect()->back()->with('version_conflict', 'Sản phẩm đã được cập nhật ở tab khác. Vui lòng tải lại trang để lấy dữ liệu mới nhất.');
        }

        // Kiểm tra chuỗi chỉ chứa khoảng trắng (kể cả khoảng trắng Unicode)
        if (preg_match('/^\s*$/u', $request->product_name)) {
            return back()->withErrors(['product_name' => 'Tên sản phẩm không được chỉ chứa khoảng trắng.'])->withInput();
        }
        if ($request->description !== null && preg_match('/^\s*$/u', $request->description)) {
            return back()->withErrors(['description' => 'Mô tả sản phẩm không được chỉ chứa khoảng trắng.'])->withInput();
        }


        // Cập nhật dữ liệu
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

        // Cập nhật ảnh nếu có
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

        // Tăng version
        $product->version = $product->version + 1;

        $product->save();

        return redirect()->route('products.read', $product->product_id)
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }


    /**
     * Xóa sản phẩm
     */
    public function destroy($id)
    {
        // Truy vấn chính xác theo cột product_id
        $product = Product::where('product_id', $id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã bị xóa.'
            ]);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa.'
        ]);
    }
}
