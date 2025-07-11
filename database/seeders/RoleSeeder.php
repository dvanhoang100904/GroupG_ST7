<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('roles')->insert([
            [
                'role_id' => 1,
                'name' => 'Admin',
            ],
            [
                'role_id' => 2,
                'name' => 'Customer',
            ],
        ]);
    }
}
