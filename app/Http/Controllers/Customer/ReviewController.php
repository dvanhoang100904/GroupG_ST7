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
    public function edit($reviewId)
    {
        $review = Review::findOrFail($reviewId);

        if (Auth::id() !== $review->user_id) {
            return back()->with('error', 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        return view('customer.pages.editreview', compact('review'));
    }

    // Cập nhật đánh giá
    public function update(Request $request, $reviewId)
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

        return redirect()->route('products.detail', ['slug' => $review->product->slug])
            ->with('success', 'Đánh giá đã được cập nhật.');
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
    public function index(Request $request)

    {
        $query = Review::with(['user', 'chat']);
        // Lọc phân loại nếu có
        if ($request->has('type') && in_array($request->type, ['review', 'chat'])) {
            $query->where('type', $request->type);
        }

        // Lọc theo số sao nếu có
        if ($request->has('rating') && is_numeric($request->rating)) {
            $query->where('rating', $request->rating);
        }

        // Phân trang 10 dòng mỗi trang
        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.content.website.website', compact('reviews'));
    }
    public function show($reviewId)
    {
        $review = Review::with(['user', 'product'])->findOrFail($reviewId);

        return view('admin.content.website.detail', compact('review'));
    }
    public function replyForm($reviewId)
    {
        $review = Review::with('user')->findOrFail($reviewId);
        return view('admin.content.website.reply', compact('review'));
    }
    public function storeReply(Request $request, $reviewId)
    {
        $request->validate([
            'reply_content' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($reviewId);

        Review::create([
            'user_id' => auth()->id(), // hoặc cố định admin id
            'product_id' => $review->product_id,
            'chat_id' => $review->id, // gán chat_id để làm liên kết mềm
            'type' => 'reply',
            'content' => $request->reply_content,
        ]);

        return response()->json(['message' => 'Phản hồi đã được gửi!']);
    }
}
