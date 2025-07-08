<?php

namespace Database\Factories;

use App\Models\Petition;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Petition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'user_id' => User::factory(),
            'signatures_required' => $this->faker->numberBetween(10, 100),
            'duration' => $this->faker->numberBetween(7, 30),
            'status' => $this->faker->randomElement(['active', 'approved', 'rejected', 'pending']),
            'school_class_id' => SchoolClass::factory(),
        ];
    }

    /**
     * Indicate that the petition is active.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
            ];
        });
    }

    /**
     * Indicate that the petition is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Indicate that the petition is approved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
            ];
        });
    }

    /**
     * Indicate that the petition is rejected.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
            ];
        });
    }
} 