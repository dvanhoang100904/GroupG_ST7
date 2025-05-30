<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $productData = [
            'Điện thoại' => [
                'products' => [
                    'iPhone 14 Pro',
                    'Samsung Galaxy S23',
                    'Xiaomi 13',
                    'OPPO Reno8',
                    'Vivo V25',
                    'Realme 11 Pro',
                    'Nokia G60',
                    'Asus ROG Phone 6',
                    'Motorola Edge',
                    'Sony Xperia 1'
                ],
                'description' => 'Chiếc điện thoại thông minh với hiệu năng mạnh mẽ, camera chất lượng cao và thiết kế sang trọng.'
            ],
            'Laptop' => [
                'products' => [
                    'MacBook Pro M2',
                    'Dell XPS 13',
                    'HP Spectre x360',
                    'Asus ROG Zephyrus',
                    'Lenovo ThinkPad X1',
                    'Acer Swift 3',
                    'MSI Stealth 15M',
                    'Huawei MateBook X',
                    'LG Gram 17',
                    'Surface Laptop 5'
                ],
                'description' => 'Laptop cao cấp, thiết kế mỏng nhẹ, cấu hình mạnh mẽ, phù hợp cho học tập và làm việc.'
            ],
            'Âm thanh (tai nghe, loa)' => [
                'products' => [
                    'Sony WH-1000XM4',
                    'Bose QuietComfort 45',
                    'JBL Charge 5',
                    'Sennheiser Momentum 3',
                    'Beats Studio3'
                ],
                'description' => 'Tai nghe và loa chất lượng cao, âm thanh sống động, thiết kế tiện dụng cho người yêu thích âm nhạc.'
            ],
            'Đồng hồ' => [
                'products' => [
                    'Apple Watch Series 8',
                    'Samsung Galaxy Watch 5',
                    'Garmin Fenix 7',
                    'Fitbit Charge 5',
                    'Amazfit GTR 3'
                ],
                'description' => 'Đồng hồ thông minh, kết hợp giữa thời trang và công nghệ, theo dõi sức khỏe và thể thao.'
            ],
            'Đồ gia dụng' => [
                'products' => [
                    'Máy xay sinh tố Philips HR2223',
                    'Nồi chiên không dầu Xiaomi',
                    'Bình đun nước điện Kangaroo',
                    'Máy lọc không khí Sharp',
                    'Máy giặt Samsung'
                ],
                'description' => 'Các sản phẩm đồ gia dụng chất lượng cao, giúp cuộc sống trở nên tiện lợi hơn.'
            ],
            'PC - Màn hình' => [
                'products' => [
                    'PC Gaming ASUS ROG',
                    'Màn hình Dell UltraSharp 27',
                    'PC HP Omen 30L',
                    'Màn hình LG 32GN650',
                    'PC Lenovo ThinkCentre'
                ],
                'description' => 'Máy tính để bàn và màn hình với cấu hình mạnh mẽ, phù hợp cho công việc và giải trí.'
            ],
            'Tivi' => [
                'products' => [
                    'Samsung QLED 4K',
                    'LG OLED C1',
                    'Sony Bravia 55X80J',
                    'Tivi Xiaomi Mi TV 4S',
                    'Tivi Sharp Aquos 4K'
                ],
                'description' => 'Tivi thông minh với chất lượng hình ảnh sắc nét, mang đến trải nghiệm xem tuyệt vời.'
            ]
        ];

        // Lấy danh sách các danh mục từ bảng `categories`
        $categories = DB::table('categories')->get();

        foreach ($categories as $category) {
            // Tên danh mục (ví dụ: "Điện thoại")
            $categoryName = $category->category_name;

            // Tạo slug từ tên danh mục (vd: "điện thoại" => "dien-thoai")
            $categorySlug = strtolower(Str::slug($categoryName));

            // Đường dẫn đến thư mục ảnh của danh mục trong thư mục `public/images`
            $categoryPath = public_path("images/{$categorySlug}");

            // Nếu thư mục chưa tồn tại thì tạo mới
            if (!is_dir($categoryPath)) {
                mkdir($categoryPath, 0777, true);
            }

            // Lấy danh sách sản phẩm mẫu & mô tả tương ứng với danh mục hiện tại
            $products = $productData[$categoryName]['products'] ?? [];
            $desc = $productData[$categoryName]['description'] ?? 'Sản phẩm chất lượng cao.';

            // Nếu chưa đủ 100 sản phẩm thì thêm sản phẩm giả để đủ
            $existingCount = count($products);
            $total = 100;

            for ($i = $existingCount + 1; $i <= $total; $i++) {
                $products[] = "{$categoryName} {$i}";
            }

            // Duyệt từng sản phẩm để insert vào DB
            foreach ($products as $productName) {
                $productSlug = Str::slug($productName); // Tạo slug từ tên sản phẩm
                $imageFile = "{$categoryPath}/{$productSlug}.jpg"; // Đường dẫn ảnh cụ thể

                // Nếu ảnh sản phẩm tồn tại thì dùng, nếu không thì dùng ảnh mặc định
                $imagePath = file_exists($imageFile)
                    ? "images/{$categorySlug}/{$productSlug}.jpg"
                    : "images/{$categorySlug}/mac-dinh.jpg";

                // Chèn sản phẩm vào bảng `products`
                DB::table('products')->insert([
                    'product_name' => $productName,
                    'slug' => $productSlug,
                    'description' => $desc,
                    'image' => $imagePath,
                    'price' => rand(2, 30) * 1000000, // Giá ngẫu nhiên từ 2 - 30 triệu
                    'category_id' => $category->category_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
