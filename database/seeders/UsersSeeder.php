<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'              => 'test',
            'email'             => 'test@test.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('1111'),
        ]);

        User::create([
            'name'              => 'demo',
            'email'             => 'demo@demo.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('1111'),
        ]);
    }
}
