<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
            $shippingAddress = ShippingAddress::where('user_id', $user->user_id)->first();

            if ($shippingAddress) {
                for ($i = 0; $i < 3; $i++) {
                    Order::create([
                        'total_price' => $faker->numberBetween(100, 5000) * 1000,
                        'status' => $faker->randomElement(['chờ_xử_lý', 'đang_xử_lý', 'đang_vận_chuyển', 'đã_giao_hàng', 'đã_hủy']),
                        'notes' => $faker->sentence(5),
                        'user_id' => $user->user_id,
                        'shipping_address_id' => $shippingAddress->shipping_address_id,
                    ]);
                }
            }
        }
    }
}
