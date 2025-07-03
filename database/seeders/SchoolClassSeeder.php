<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
        for ($i = 1; $i <= 11; $i++) {
            foreach ($letters as $letter) {
                SchoolClass::create(['class_number' => $i, 'class_letter' => $letter]);
            }
        }
    }
}
