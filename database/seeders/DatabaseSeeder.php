<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
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
            'nim' => '125354544',
            'first_name' => 'Ilham',
            'last_name' => 'Ganteng',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'status' => true,
            'due_block' => null,
            'role' => 'user',
        ]);

        User::create([
            'nim' => null,
            'first_name' => 'Bos',
            'last_name' => 'Jhodie',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
            'status' => true,
            'due_block' => null,
            'role' => 'admin',
        ]);

        User::create([
            'nim' => null,
            'first_name' => 'Angga',
            'last_name' => 'Doe',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'status' => true,
            'due_block' => null,
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => '3',
            'role' => 'super_admin',
        ]);

        Admin::create([
            'user_id' => '2',
            'role' => 'admin',
        ]);
    }
}
