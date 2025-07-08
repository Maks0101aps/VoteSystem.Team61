<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем аккаунт для тестовых пользователей
        $account = Account::create([
            'name' => 'Test School'
        ]);

        // Директор
        User::create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'director',
        ]);

        // Учитель
        User::create([
            'account_id' => $account->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'teacher@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Родитель
        User::create([
            'account_id' => $account->id,
            'first_name' => 'Peter',
            'last_name' => 'Jones',
            'email' => 'parent@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'parent',
        ]);

        // Ученик
        User::create([
            'account_id' => $account->id,
            'first_name' => 'Sam',
            'last_name' => 'Wilson',
            'email' => 'student@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
} 