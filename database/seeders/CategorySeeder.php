<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [
            [
                'name' => 'Điện thoại, Tablet',
                'image' => 'phones.jpg'
            ],
            [
                'name' => 'Laptop',
                'image' => 'laptops.jpg'
            ],
            [
                'name' => 'Phụ kiện',
                'image' => 'accessories.jpg'
            ],
        ];
        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']) . '-' . uniqid(),
                'description' => $faker->sentence(20),
                'image' => $category['image'],
            ]);
        }
    }
}
