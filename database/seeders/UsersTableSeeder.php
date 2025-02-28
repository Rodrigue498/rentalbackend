<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $users = [
            [
                'name' => 'John Doe',
                'email' => $faker->unique()->safeEmail,
                'address'=>'123 Main market Lahore',
                'phone'=>'03314216767',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ],
            [
                'name' => 'Jane Smith',
                'email' => $faker->unique()->safeEmail,
                'address'=>'123 Main market Lahore',
                'phone'=>'03314216763',
                'password' => Hash::make('password'),
                'role' => 'renter',
            ],
            [
                'name' => 'Admin User',
                'email' => $faker->unique()->safeEmail,
                'address'=>'123 Main market Lahore',
                'phone'=>'03314216744',
                'password' => Hash::make('password'),
                'role' => 'administrator',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
