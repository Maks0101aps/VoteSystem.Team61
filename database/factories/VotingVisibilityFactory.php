<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Voting;
use App\Models\VotingVisibility;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotingVisibilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VotingVisibility::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'voting_id' => Voting::factory(),
            'role' => $this->faker->randomElement(['student', 'teacher', 'admin']),
            'class_number' => $this->faker->numberBetween(1, 11),
            'class_letter' => $this->faker->randomElement(['А', 'Б', 'В', 'Г', 'Д']),
        ];
    }
    
    /**
     * Configure the visibility for a specific voting
     */
    public function forVoting(Voting $voting)
    {
        return $this->state(function (array $attributes) use ($voting) {
            return [
                'voting_id' => $voting->id,
            ];
        });
    }
    
    /**
     * Configure the visibility for a specific class
     */
    public function forClass($classNumber, $classLetter)
    {
        return $this->state(function (array $attributes) use ($classNumber, $classLetter) {
            return [
                'class_number' => $classNumber,
                'class_letter' => $classLetter,
            ];
        });
    }
    
    /**
     * Configure the visibility for a specific role
     */
    public function forRole($role)
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'role' => $role,
                'class_number' => null,
                'class_letter' => null,
            ];
        });
    }
} 