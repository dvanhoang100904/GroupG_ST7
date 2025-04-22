<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 

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
            $categorySlug = Str::slug($category);

            DB::table('categories')->insert([
                'category_name' => $category,
                'slug' => $categorySlug,
                'description' => 'Mô tả cho ' . $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
