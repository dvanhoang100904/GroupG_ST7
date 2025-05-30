<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            $selectedProducts = $products->random(rand(1, 3));
            $total = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 5);
                $price = $product->price;
                $lineTotal = $price * $quantity;

                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            // Cập nhật total_price cho đơn hàng
            $order->update(['total_price' => $total]);
        }
    }
}
