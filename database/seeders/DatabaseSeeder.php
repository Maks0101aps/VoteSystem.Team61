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
        // Очищаем таблицы
        \DB::statement('DELETE FROM users');
        \DB::statement('UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME="users"');
        \DB::statement('DELETE FROM accounts');
        \DB::statement('UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME="accounts"');
        
        // Запускаем сидеры
        $this->call(SchoolClassSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
