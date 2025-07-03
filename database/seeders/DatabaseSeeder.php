<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'role' => 'director',
        ]);

        User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'teacher@example.com',
            'password' => 'secret',
            'role' => 'teacher',
        ]);

        User::factory()->create([
            'first_name' => 'Peter',
            'last_name' => 'Jones',
            'email' => 'parent@example.com',
            'password' => 'secret',
            'role' => 'parent',
        ]);

        User::factory()->create([
            'first_name' => 'Sam',
            'last_name' => 'Wilson',
            'email' => 'student@example.com',
            'password' => 'secret',
            'role' => 'student',
        ]);
    }
}
