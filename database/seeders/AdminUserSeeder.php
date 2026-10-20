<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@manufaktur.com'], // Cek berdasarkan email agar tidak duplikat jika dijalankan ulang
            [
                'name' => 'Administrator HR',
                'password' => Hash::make('password123'), // Password akun admin
                'email_verified_at' => now(),
            ]
        );
    }
}