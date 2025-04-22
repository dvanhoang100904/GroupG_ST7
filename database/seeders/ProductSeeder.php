<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $categoryIds = Category::pluck('category_id')->all();


        for ($i = 1; $i <= 30; $i++) {
            $name = $faker->sentence(3);

            Product::create([
                'name'  => $name,
                'description' => $faker->paragraph(),
                'price' => $faker->numberBetween(100, 5000) * 1000,
                'image' => 'products/' . $faker->image(storage_path('app/public/products'), 400, 300, null, false),
                'category_id' => $faker->randomElement($categoryIds),
                'slug' => Str::slug($name) . '-' . uniqid(),

            ]);
        }
    }
}
