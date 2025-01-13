<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Trailer;

class TrailerTableSeeder extends Seeder
{
        public function run()
        {
            // Example trailers data
            $trailers = [
                [
                    'user_id' => 1,
                    'title' => 'Premium Enclosed Trailer',
                    'description' => 'A top-notch enclosed trailer with spacious capacity and modern features.',
                    'type' => 'Enclosed',
                    'features' => 'GPS, Climate season Control, Anti-Theft Lock',
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
                    'user_id' => 2,
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
                    'user_id' => 3, // Assuming this user exists in the database
                    'title' => 'Heavy-Duty Car Hauler',
                    'description' => 'Ideal for transporting vehicles, with a robust frame and ramp.',
                    'type' => 'Car Hauler',
                    'features' => 'Hydraulic Ramp, Heavy-Duty Tires',
                    'size' => 18.0, // Size in feet
                    'capacity' => 3000, // Capacity in kilograms
                    'available' => true,
                    'approval_status' => 'pending',
                    'admin_feedback' => null,
                    'price' => 75.00, // Price per day
                    'images' => json_encode([
                        'uploads/trailers/hauler1.jpg',
                        'uploads/trailers/hauler2.jpg',
                    ]),
                ],
            ];

            foreach ($trailers as $trailer) {
                Trailer::create($trailer);
            }
        }
    }


