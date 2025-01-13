<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Trailer;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch some users and trailers
        $users = User::all();
        $trailers = Trailer::all();

        if ($users->isEmpty() || $trailers->isEmpty()) {
            $this->command->warn('No users or trailers found. Please seed them first.');
            return;
        }

        // Create reviews
        foreach ($trailers as $trailer) {
            foreach ($users->take(3) as $user) { // Add 3 reviews per trailer
                Review::create([
                    'user_id' => $user->id,
                    'trailer_id' => $trailer->id,
                    'content' => 'This is a sample review for trailer ' . $trailer->id . '.',
                    'rating' => rand(1, 5), // Random rating between 1 and 5
                ]);
            }
        }

        $this->command->info('Reviews seeded successfully!');
    }
}
