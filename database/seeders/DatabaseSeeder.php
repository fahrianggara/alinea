<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Book;
use App\Models\Category;
use App\Models\Status;
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
        Book::factory()->count(10)->create();



        User::create([
            'nim' => '125354544',
            'image' => 'profile/default.png',
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
            'image' => 'profile/default.png',
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
            'image' => 'profile/default.png',
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


        Status::create([
            'name' => 'pending',
            'color' => 'bg-warning',
            'message' => 'Your payment is still pending. Please complete the payment to proceed.',
            'description' => 'waiting for payment',
        ]);

        Status::create([
            'name' => 'borrowed',
            'color' => 'bg-primary',
            'message' => 'The book has been successfully borrowed. Enjoy reading!',
            'description' => 'book borrowed',
        ]);

        Status::create([
            'name' => 'returned',
            'color' => 'bg-success',
            'message' => 'Thank you for returning the book. We hope you enjoyed it!',
            'description' => 'book returned',
        ]);

        Status::create([
            'name' => 'late return',
            'color' => 'bg-warning',
            'message' => 'The book was returned late. Please check our policy on late returns.',
            'description' => 'book late returned',
        ]);

        Status::create([
            'name' => 'missing',
            'color' => 'bg-danger',
            'message' => 'The book is currently marked as missing. Please report it if you find it.',
            'description' => 'book missing',
        ]);

        Status::create([
            'name' => 'damaged',
            'color' => 'bg-danger',
            'message' => 'The book is currently marked as damaged. Please report it',
            'description' => 'book missing',
        ]);


    }
}
