<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'staff@inventory.test'],
            [
                'name' => 'Staf Operasional',
                'username' => 'staff',
                'password' => 'Staff123!',
                'role' => 'staff',
            ]
        );
    }
}
