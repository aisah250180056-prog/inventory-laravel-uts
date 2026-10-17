<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => 'Admin123!',
                'role' => 'admin',
            ]
        );
    }
}
