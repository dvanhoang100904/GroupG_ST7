<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();
        $products = DB::table('products')->get();

        $comments = [
            'Sản phẩm rất tốt, giao hàng nhanh.',
            'Chất lượng ổn, đúng mô tả.',
            'Tạm ổn, giá phù hợp với chất lượng.',
            'Không hài lòng lắm, sản phẩm có lỗi nhẹ.',
            'Xuất sắc, sẽ ủng hộ lần sau!',
            'Sản phẩm giống hình, đáng tiền.',
            'Nhân viên tư vấn nhiệt tình, sẽ quay lại.',
            'Hàng đẹp, xài mượt mà.',
            'Còn nguyên seal, đúng chính hãng.',
            'Giao nhanh, đóng gói cẩn thận.'
        ];

        foreach ($products as $product) {
            foreach ($users->random(3) as $user) {
                DB::table('reviews')->insert([
                    'content' => $comments[array_rand($comments)],
                    'rating' => rand(3, 5),
                    'type' => 'review',
                    'photo' => null,
                    'user_id' => $user->user_id,
                    'product_id' => $product->product_id,
                    'chat_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
