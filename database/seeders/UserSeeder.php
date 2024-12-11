<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'role' => 'admin',
            'email' => 'adminapotek@gmail.com',
            'password' => Hash::make('admin123'),
        ]);
        User::create([
            'name' => 'Kasir',
            'role' => 'kasir',
            'email' => 'kasirapotek@gmail.com',
            'password' => Hash::make('kasir123'),
        ]);
    }
}
