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
        \DB::statement('DELETE FROM school_classes');
        \DB::statement('UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME="school_classes"');
        
        $letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
        for ($i = 1; $i <= 11; $i++) {
            foreach ($letters as $letter) {
                SchoolClass::create(['class_number' => $i, 'class_letter' => $letter]);
            }
        }
    }
}
