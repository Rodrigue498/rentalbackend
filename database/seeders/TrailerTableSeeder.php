<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Trailer;
use App\Models\User;

class TrailerTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure users exist before inserting trailers
        $user1 = User::firstOrCreate(['id' => 1], [
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::firstOrCreate(['id' => 2], [
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);

        $user3 = User::firstOrCreate(['id' => 3], [
            'name' => 'User Three',
            'email' => 'user3@example.com',
            'password' => bcrypt('password'),
        ]);

        // Example trailers data
        $trailers = [
            [
                'user_id' => $user1->id,
                'title' => 'Premium Enclosed Trailer',
                'description' => 'A top-notch enclosed trailer with spacious capacity and modern features.',
                'type' => 'Enclosed',
                'features' => 'GPS, Climate Control, Anti-Theft Lock',
                'size' => 12.5,
                'capacity' => 1500,
                'available' => true,
                'price' => 50.00,
                'approval_status' => 'pending',
                'admin_feedback' => null,
                'images' => json_encode([
                    'uploads/trailers/enclosed1.jpg',
                    'uploads/trailers/enclosed2.jpg',
                ]),
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Open Utility Trailer',
                'description' => 'An open utility trailer perfect for transporting goods and equipment.',
                'type' => 'Utility',
                'features' => 'Durable Build, Removable Sides',
                'size' => 10.0,
                'capacity' => 1000,
                'available' => true,
                'approval_status' => 'pending',
                'admin_feedback' => null,
                'price' => 30.00,
                'images' => json_encode([
                    'uploads/trailers/utility1.jpg',
                    'uploads/trailers/utility2.jpg',
                ]),
            ],
            [
                'user_id' => $user3->id,
                'title' => 'Heavy-Duty Car Hauler',
                'description' => 'Ideal for transporting vehicles, with a robust frame and ramp.',
                'type' => 'Car Hauler',
                'features' => 'Hydraulic Ramp, Heavy-Duty Tires',
                'size' => 18.0,
                'capacity' => 3000,
                'available' => true,
                'approval_status' => 'pending',
                'admin_feedback' => null,
                'price' => 75.00,
                'images' => json_encode([
                    'uploads/trailers/hauler1.jpg',
                    'uploads/trailers/hauler2.jpg',
                ]),
            ],
        ];

        // Insert trailers
        foreach ($trailers as $trailer) {
            Trailer::create($trailer);
        }
    }
}
