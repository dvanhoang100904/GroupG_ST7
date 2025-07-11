<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlideSeeder extends Seeder
{
    public function run()
    {
        // Danh sách slide mẫu
        $slides = [
            [
                'name' => 'Slide Giới thiệu',
                'image' => 'img_slide/banner4.jpeg',
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Slide Khuyến mãi',
                'image' => 'img_slide/banner2.jpg',
                'is_visible' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Thêm vào bảng slides
        DB::table('slides')->insert($slides);
    }
}
