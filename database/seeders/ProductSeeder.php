<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu sản phẩm mẫu theo các danh mục
        $productData = [
            'Điện thoại' => [
                'products' => [
                    'iPhone 14 Pro', 'Samsung Galaxy S23', 'Xiaomi 13', 'OPPO Reno8', 'Vivo V25',
                    'Realme 11 Pro', 'Nokia G60', 'Asus ROG Phone 6', 'Motorola Edge', 'Sony Xperia 1'
                ],
                'description' => 'Chiếc điện thoại thông minh với hiệu năng mạnh mẽ, camera chất lượng cao và thiết kế sang trọng.'
            ],
            'Laptop' => [
                'products' => [
                    'MacBook Pro M2', 'Dell XPS 13', 'HP Spectre x360', 'Asus ROG Zephyrus', 'Lenovo ThinkPad X1',
                    'Acer Swift 3', 'MSI Stealth 15M', 'Huawei MateBook X', 'LG Gram 17', 'Surface Laptop 5'
                ],
                'description' => 'Laptop cao cấp, thiết kế mỏng nhẹ, cấu hình mạnh mẽ, phù hợp cho học tập và làm việc.'
            ],
            'Âm thanh (tai nghe, loa)' => [
                'products' => [
                    'Sony WH-1000XM4', 'Bose QuietComfort 45', 'JBL Charge 5', 'Sennheiser Momentum 3', 'Beats Studio3'
                ],
                'description' => 'Tai nghe và loa chất lượng cao, âm thanh sống động, thiết kế tiện dụng cho người yêu thích âm nhạc.'
            ],
            'Đồng hồ' => [
                'products' => [
                    'Apple Watch Series 8', 'Samsung Galaxy Watch 5', 'Garmin Fenix 7', 'Fitbit Charge 5', 'Amazfit GTR 3'
                ],
                'description' => 'Đồng hồ thông minh, kết hợp giữa thời trang và công nghệ, theo dõi sức khỏe và thể thao.'
            ],
            'Đồ gia dụng' => [
                'products' => [
                    'Máy xay sinh tố Philips HR2223', 'Nồi chiên không dầu Xiaomi', 'Bình đun nước điện Kangaroo', 'Máy lọc không khí Sharp', 'Máy giặt Samsung'
                ],
                'description' => 'Các sản phẩm đồ gia dụng chất lượng cao, giúp cuộc sống trở nên tiện lợi hơn.'
            ],
            'PC - Màn hình' => [
                'products' => [
                    'PC Gaming ASUS ROG', 'Màn hình Dell UltraSharp 27', 'PC HP Omen 30L', 'Màn hình LG 32GN650', 'PC Lenovo ThinkCentre'
                ],
                'description' => 'Máy tính để bàn và màn hình với cấu hình mạnh mẽ, phù hợp cho công việc và giải trí.'
            ],
            'Tivi' => [
                'products' => [
                    'Samsung QLED 4K', 'LG OLED C1', 'Sony Bravia 55X80J', 'Tivi Xiaomi Mi TV 4S', 'Tivi Sharp Aquos 4K'
                ],
                'description' => 'Tivi thông minh với chất lượng hình ảnh sắc nét, mang đến trải nghiệm xem tuyệt vời.'
            ]
        ];

       
        // Lấy tất cả danh mục từ bảng 'categories'
        $categories = DB::table('categories')->get();

        // Duyệt qua từng danh mục để thêm sản phẩm tương ứng
        foreach ($categories as $category) {
            // Lấy danh sách sản phẩm và mô tả của danh mục từ dữ liệu mẫu
            $products = $productData[$category->category_name]['products'] ?? [];
            $desc = $productData[$category->category_name]['description'] ?? 'Sản phẩm chất lượng cao.';

            // Tạo thư mục cho từng danh mục nếu chưa có
            $categoryFolder = strtolower(Str::slug($category->category_name));
            $categoryPath = public_path('images/' . $categoryFolder);
            if (!is_dir($categoryPath)) {
                mkdir($categoryPath, 0777, true); // Tạo thư mục nếu chưa tồn tại
            }

            // Thêm các sản phẩm vào bảng 'products'
            foreach ($products as $productName) {
                // Tạo slug cho tên sản phẩm và đặt tên ảnh
                $productSlug = Str::slug($productName);
                $imagePath = 'images/' . $categoryFolder . '/' . $productSlug . '.jpg';  // Đường dẫn đến thư mục con tương ứng

                DB::table('products')->insert([
                    'product_name' => $productName,  // Tên sản phẩm
                    'description' => $desc,          // Mô tả sản phẩm
                    'image' => $imagePath,           // Đường dẫn ảnh (trong thư mục con)
                    'price' => rand(2, 30) * 1000000, // Giá sản phẩm (random từ 2 đến 30 triệu VND)
                    'category_id' => $category->category_id, // ID danh mục sản phẩm
                    'created_at' => now(),           // Thời gian tạo
                    'updated_at' => now(),           // Thời gian cập nhật
                ]);
            }
        }
    }
}
