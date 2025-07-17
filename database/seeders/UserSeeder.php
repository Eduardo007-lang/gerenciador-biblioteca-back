<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'registration_number' => 123456,
            'password' => Hash::make('Admin1234!'),
        ]);
        User::create([
            'name' => 'Eduardo',
            'email' => 'edu@gmail.com',
            'registration_number' => 123444,
            'password' => Hash::make('Admin1234!'),
        ]);
    }
} 