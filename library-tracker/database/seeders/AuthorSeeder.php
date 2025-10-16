<?php

namespace Database\Seeders;

use App\Models\Author;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 30) as $index) {
            Author::create([
                'first_name' => $faker->firstName(),
                'last_name'  => $faker->lastName(),
                'biography'  => $faker->paragraph(2, true),
            ]);
        }
    }
}
