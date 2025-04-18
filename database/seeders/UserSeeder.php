<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'phone' => '012345781',
                'avatar' => null,
                'role_id' => 1, // Admin
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Hoàng',
                'phone' => '0123456782',
                'avatar' => null,
                'role_id' => 2, // Customer
                'email' => 'hoang@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'dân',
                'phone' => '0123456783',
                'avatar' => null,
                'role_id' => 2, // Customer
                'email' => 'dan@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Yên',
                'phone' => '0123456784',
                'avatar' => null,
                'role_id' => 2, // Customer
                'email' => 'yen@gmail.com',
                'password' => Hash::make('123456'),

            ],
            [
                'name' => 'Lâm',
                'phone' => '0123456785',
                'avatar' => null,
                'role_id' => 2, // Customer
                'email' => 'lam@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Tâm',
                'phone' => '0123456786',
                'avatar' => null,
                'role_id' => 2, // Customer
                'email' => 'tam@gmail.com',
                'password' => Hash::make('123456'),

            ],

        ]);
    }
}
