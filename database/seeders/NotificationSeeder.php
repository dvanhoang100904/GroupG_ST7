<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy 5 user bất kỳ (nhớ phải có user trong DB rồi nha)
        $users = User::inRandomOrder()->take(5)->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->user_id,
                'title' => '🎉 Khuyến mãi đặc biệt!',
                'content' => 'Giảm giá 20% cho tất cả sản phẩm từ hôm nay đến hết tuần. Nhanh tay nào!',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $user->user_id,
                'title' => '🔥 Deal sốc trong ngày!',
                'content' => 'Chỉ hôm nay! Mua 1 tặng 1 áp dụng cho sản phẩm HOT nhất tháng!',
                'is_read' => false,
            ]);
        }
    }
}
