<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $pass  = env('ADMIN_PASSWORD', 'password123'); // 本番は必ず変更
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => 'Admin', 'password' => Hash::make($pass)]
        );
        if (! $user->is_admin) {
            $user->is_admin = true;
            $user->save();
        }
    }
}
