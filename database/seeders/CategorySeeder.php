<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu danh mục sản phẩm
        $categories = [
            'Điện thoại',
            'Laptop',
            'Âm thanh (tai nghe, loa)',
            'Đồng hồ',
            'Đồ gia dụng',
            'PC - Màn hình',
            'Tivi'
        ];

        // Duyệt qua từng danh mục để thêm vào cơ sở dữ liệu
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                // Tên danh mục
                'category_name' => $category,

                // Mô tả của danh mục (sử dụng tên danh mục để tạo mô tả)
                'description' => 'Mô tả cho ' . $category,

                // Thời gian tạo
                'created_at' => now(),

                // Thời gian cập nhật
                'updated_at' => now(),
            ]);
        }
    }
}
