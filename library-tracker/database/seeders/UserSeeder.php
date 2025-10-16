<?php

namespace Database\Seeders;

use Hash;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 15) as $index) {
            User::create([
                'name'              => substr($faker->name(), 0, 100),
                'email'             => substr($faker->unique()->safeEmail(), 0, 100),
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'remember_token'    => null,
            ]);
        }
    }
}
