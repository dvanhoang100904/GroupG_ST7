<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\slide;
use App\Models\Category;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $productCount = Product::count();
        $orderCount = Order::count();
        $adminCount = User::where('role_id', 1)->count();
        $userCount = User::where('role_id', 2)->count();
        $slideCount = slide::count();
        $categoryCount = Category::count();
        return view('admin.content.dashboard.index', compact(
            'productCount',
            'orderCount',
            'adminCount',
            'userCount',
            'slideCount',
            'categoryCount'
        ));
    }
}
