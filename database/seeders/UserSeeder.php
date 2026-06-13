<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@mananfurniture.com'],
            [
                'name'     => 'Ramesh Kumar Suthar',
                'password' => Hash::make('Manan@2024'),
            ]
        );
    }
}
