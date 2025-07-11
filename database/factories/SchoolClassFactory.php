<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SchoolClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'class_number' => $this->faker->numberBetween(1, 11),
            'class_letter' => $this->faker->randomElement(['А', 'Б', 'В', 'Г', 'Д']),
        ];
    }
} 