<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class TrangChuController extends Controller
{
    public function index()
    {
        $hotSaleProducts = Product::latest()->limit(10)->get();

        return view('review.trangchu', compact('hotSaleProducts'));
    }
}
