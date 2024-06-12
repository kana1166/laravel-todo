<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        foreach ($users as $user) {
            echo "User Email: " . $user->email . PHP_EOL;
            echo "User Password: password" . PHP_EOL; // 全てのユーザーに共通のパスワードを表示
        }

        $this->call([
            PostSeeder::class,
        ]);
    }
}
