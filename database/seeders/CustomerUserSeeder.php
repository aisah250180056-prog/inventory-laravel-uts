<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'customer@inventory.test'],
            [
                'name' => 'Budi Santoso (Customer)',
                'username' => 'customer',
                'password' => 'Customer123!',
                'role' => 'customer',
            ]
        );
    }
}
