<?php

namespace Database\Seeders;

use App\Models\{Author, Book};
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $author_ids = Author::pluck('id')->toArray();
        $genres = [
            'Fiction',
            'Non-Fiction',
            'Fantasy',
            'Science Fiction',
            'Mystery',
            'Romance',
            'Biography',
            'History',
        ];

        foreach (range(1, 50) as $index) {
            Book::create([
                'author_id'    => $faker->randomElement($author_ids),
                'title'        => substr($faker->sentence(3), 0, 100),
                'genre'        => $faker->randomElement($genres),
                'isbn'         => $faker->unique()->isbn13(),
                'published_at' => $faker->dateTimeBetween('1970-01-01', '2025-12-31'),
            ]);
        }
    }
}
