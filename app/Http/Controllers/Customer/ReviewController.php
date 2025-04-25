<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    // Gửi đánh giá
    // public function store(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return response()->json([
    //             'status' => 'unauthenticated',
    //             'message' => 'Bạn cần đăng nhập để đánh giá sản phẩm.'
    //         ]);
    //     }

    //     $request->validate([
    //         'product_id' => 'required|exists:products,product_id',
    //         'content' => 'required|string|max:1000',
    //         'rating' => 'required|integer|min:1|max:5',
    //         'photo' => 'nullable|image|max:2048',
    //         // Không cần validate chat_id vì nó optional
    //     ]);

    //     $photoPath = null;
    //     if ($request->hasFile('photo')) {
    //         $photo = $request->file('photo');
    //         $fileName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();

    //         $destination = public_path('images/reviews');
    //         if (!File::exists($destination)) {
    //             File::makeDirectory($destination, 0755, true);
    //         }

    //         $photo->move($destination, $fileName);
    //         $photoPath = 'images/reviews/' . $fileName;
    //     }

    //     try {
    //         Review::create([
    //             'user_id' => Auth::id(),
    //             'product_id' => $request->product_id,
    //             'content' => $request->content,
    //             'rating' => $request->rating,
    //             'photo' => $photoPath,
    //             'type' => 'product',
    //             'chat_id' => $request->chat_id ?? null, // ✅ thêm vào đây
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         // return response()->json([
    //         //     'status' => 'success',
    //         //     'message' => 'Đánh giá đã được gửi thành công!'
    //         // ]);
    //         $product = Product::findOrFail($productId);

    //         return redirect()
    //             ->route('products.detail', ['slug' => $product->slug])
    //             ->with('success', 'Đánh giá đã được gửi thành công!');
    //     } catch (\Exception $e) {
    //         Log::error('Lỗi khi lưu đánh giá: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Đã xảy ra lỗi khi lưu đánh giá.'
    //         ]);
    //     }
    // }
    public function store(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('products.detail', ['slug' => Product::findOrFail($productId)->slug])
                ->with('error', 'Bạn cần đăng nhập để đánh giá sản phẩm.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fileName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();

            $destination = public_path('images/reviews');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $photo->move($destination, $fileName);
            $photoPath = 'images/reviews/' . $fileName;
        }

        try {
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'content' => $request->content,
                'rating' => $request->rating,
                'photo' => $photoPath,
                'type' => 'product',
                'chat_id' => $request->chat_id ?? null,
            ]);

            $product = Product::findOrFail($productId);

            return redirect()
                ->route('products.detail', ['slug' => $product->slug])
                ->with('success', 'Đánh giá đã được gửi thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu đánh giá: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu đánh giá.');
        }
    }

    // Lấy danh sách đánh giá cho 1 sản phẩm
    public function getProductReviews($product_id)
    {
        $reviews = Review::with('user')
            ->where('product_id', $product_id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($reviews);
    }
    // Cập nhật đánh giá
    public function edit(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        if (Auth::id() !== $review->user_id) {
            return back()->with('error', 'Bạn không có quyền sửa đánh giá này.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Xóa ảnh cũ nếu có
            if ($review->photo && file_exists(public_path($review->photo))) {
                unlink(public_path($review->photo));
            }

            $photo = $request->file('photo');
            $fileName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $destination = public_path('images/reviews');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $photo->move($destination, $fileName);
            $review->photo = 'images/reviews/' . $fileName;
        }

        $review->content = $request->content;
        $review->rating = $request->rating;
        $review->save();

        return back()->with('success', 'Đánh giá đã được cập nhật.');
    }

    // Xóa đánh giá
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);

        if (Auth::id() !== $review->user_id) {
            return back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }

        if ($review->photo && file_exists(public_path($review->photo))) {
            unlink(public_path($review->photo));
        }

        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa.');
    }
}
