<?php

namespace Database\Seeders;

use App\Models\{Book, Loan, User};
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $book_ids = Book::pluck('id')->toArray();
        $user_ids = User::pluck('id')->toArray();

        foreach (range(1, 150) as $index) {
            $loaned_at = Carbon::instance(
                $faker->dateTimeBetween('2023-01-01', now()->subDays(30))
            );

            // 50% chance of being returned
            $returned_at = $faker->boolean(50)
                ? (clone $loaned_at)->addDays(rand(1, 20))
                : null;

            Loan::create([
                'book_id'     => $faker->randomElement($book_ids),
                'user_id'     => $faker->randomElement($user_ids),
                'loaned_at'   => $loaned_at,
                'returned_at' => $returned_at,
            ]);
        }
    }
}
