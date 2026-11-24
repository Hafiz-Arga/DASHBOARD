<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Membuat akun admin. Aman dijalankan berulang kali
     * (updateOrCreate berdasarkan email).
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (app()->environment('production') && (empty($email) || empty($password))) {
            throw new RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD before seeding in production.');
        }

        User::updateOrCreate(
            ['email' => $email ?: 'admin@superstore.test'],
            [
                'name' => 'Administrator',
                'password' => $password ?: 'admin12345',
            ]
        );
    }
}
