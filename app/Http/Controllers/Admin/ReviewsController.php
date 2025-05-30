<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewsController extends Controller
{
    public function listReviews()
    {
        $reviews = Review::with(['user', 'replies.user']) // eager load để tránh N+1 query
            ->where('type', 'review')
            ->paginate(10);

        return view('admin.content.website.review', compact('reviews'));
        dd($reviews);
    }
}
