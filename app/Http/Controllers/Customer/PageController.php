<?php
namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function privacyPolicy()
    {
        $slides = [
            (object)[
                'image' => 'images/banner2.jpg',
                'name' => 'Banner Chính sách bảo mật'
            ]
        ];
        return view('customer.pages.privacy-policy', compact('slides'));
    }

    public function warrantyPolicy()
    {
        $slides = [
            (object)[
                'image' => 'images/banner3.jpg',
                'name' => 'Banner Chính sách bảo hành'
            ]
        ];
        return view('customer.pages.warranty-policy', compact('slides'));
    }
}
