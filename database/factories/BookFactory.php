<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Judul buku acak dengan 3 kata
            'author' => $this->faker->name(), // Nama penulis acak
            'isbn' => $this->faker->isbn13(), // ISBN acak 13 digit
            'description' => $this->faker->paragraph(), // Deskripsi buku acak
            'published_date' => $this->faker->date(), // Tanggal publikasi acak
            'status' => $this->faker->randomElement(['available', 'borrowed']), // Status acak
            'category_id' => \App\Models\Category::factory(), // Relasi dengan kategori, buat kategori acak
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
