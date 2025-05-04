<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
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

    public function create()
    {
        $categories = Category::all();
        return view('admin.content.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
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

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('images/products', 'public');
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa.');
    }

    public function read(Product $product)
    {
        $product->load('category');
        return view('admin.content.products.read', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.content.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
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

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->image = $request->file('image')->store('images/products', 'public');
        }

        $product->save();

        return redirect()->route('products.read', $product->product_id)->with('success', 'Cập nhật sản phẩm thành công.');
    }
}
