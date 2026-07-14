<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@seminar.com',
            ],
            [
                'name' => 'Administrator',
                'no_hp' => '081234567890',
                'alamat' => 'Palembang',
                'role' => 'admin',
                'status_akun' => 'diterima',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}