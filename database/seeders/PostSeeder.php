<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Post::factory(10)->for(User::create([
            'name' => 'admin',
            'email' => 'test@test.com',
            'password' => 'pass123',
        ]))->create();
    }
}
