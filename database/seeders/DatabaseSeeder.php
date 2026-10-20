<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
{
    \App\Models\User::firstOrCreate(
        ['email' => 'admin@manufaktur.com'],
        [
            'name' => 'Admin Manufaktur',
            'password' => bcrypt('password123'),
        ]
    );

    $this->call(LandingPageSeeder::class);
}
}