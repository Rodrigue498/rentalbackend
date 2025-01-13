<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \Database\Seeders\UsersTableSeeder;
use \Database\Seeders\TrailerTableSeeder;
use \Database\Seeders\ReviewSeeder;
use \Database\Seeders\SeasonalPricingSeeder;
use \Database\Seeders\AvailabilitySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

    $this->call([
        UsersTableSeeder::class,
        TrailerTableSeeder::class,
        ReviewSeeder::class,
        AvailabilitySeeder::class,
        SeasonalPricingSeeder::class,
    ]);
    }
}
