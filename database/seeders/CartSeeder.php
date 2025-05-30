<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả người dùng
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            // Tạo session_id giả lập
            $sessionId = (string) Str::uuid();

            // Tạo cart cho mỗi user
            $cart = Cart::create([
                'user_id' => $user->user_id,
                'session_id' => $sessionId,
            ]);

            // Lấy 1-2 sản phẩm ngẫu nhiên để thêm vào giỏ
            $randomProducts = $products->random(rand(1, 2));

            foreach ($randomProducts as $product) {
                CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $product->product_id,
                    'quantity' => rand(1, 2),
                ]);
            }
        }
    }
}
