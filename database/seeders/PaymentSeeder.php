<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $faker = Faker::create();
        $orders = Order::all();

        foreach ($orders as $order) {
            Payment::create([
                'order_id' => $order->order_id,
                'method' => $faker->randomElement(['COD', 'MoMo']),
                'status' => $faker->randomElement(['đang_chờ', 'đã_thanh_toán', 'thất_bại', 'đã_hoàn_tiền']),
            ]);
        }
    }
}
