<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@perikanan.go.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('Password123'),
                'role' => 'super_admin',
                'is_root_super_admin' => true,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@perikanan.go.id'],
            [
                'name' => 'Admin Dinas',
                'password' => Hash::make('Password123'),
                'role' => 'admin',
                'is_root_super_admin' => false,
                'is_active' => true,
            ]
        );
    }
}
