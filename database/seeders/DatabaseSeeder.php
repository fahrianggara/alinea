<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory()->count(5)->create();
        Book::factory()->count(5)->create();
        User::create([
            'nim' => '1234567890',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'status' => true,
            'due_block' => null,
            'role' => 'user',
        ]);

        User::create([
            'nim' => '0987654321',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
            'status' => true,
            'due_block' => null,
            'role' => 'admin',
        ]);
    }
}
